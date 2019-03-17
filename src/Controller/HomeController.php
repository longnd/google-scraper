<?php
/**
 * This file is part of the application.
 *
 * (c) Long Nguyen <hello@longnd.me>
 *
 * Date: 2019-03-10
 * Time: 11:44
 */

namespace App\Controller;

use App\Repository\ScrapingRequestRepository;
use App\Service\ScrapingService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Contracts\Translation\TranslatorInterface;

class HomeController extends AbstractController
{
    /**
     * @Route("/", name="csvUpload")
     *
     * @return JsonResponse|\Symfony\Component\HttpFoundation\Response
     */
    public function index(Request $request, TranslatorInterface $translator, ScrapingService $scrapingService)
    {
        $form = $this->createFormBuilder()
            ->add('csvFile', FileType::class)
            ->getForm();
        if ('POST' === $request->getMethod()) {
            $form->handleRequest($request);

            if ($form->isSubmitted()) {
                if (!$form->isValid()) {
                    return new JsonResponse('error.invalid_form_submitted');
                }

                /** @var UploadedFile $file */
                $file = $form->get('csvFile')->getData();

                if ('csv' !== $file->getClientOriginalExtension() || !\in_array($file->guessExtension(), ['csv', 'txt'], true)) {
                    return new JsonResponse($translator->trans('error.only_csv_accepted'), 400);
                }

                if (!$openedFile = fopen($file->getPathname(), 'r')) {
                    return new JsonResponse($translator->trans('error.file_cannot_open'), 400);
                }

                $keywords = [];
                while ($row = fgetcsv($openedFile)) {
                    $keywords = array_merge($keywords, $row);
                }

                fclose($openedFile);

                $request = $scrapingService->createScrapingRequest($keywords);

                return new JsonResponse([
                    'success' => true,
                    'id' => $request->getId(),
                ]);
            }
        }

        return $this->render('home.html.twig', ['form' => $form->createView()]);
    }

    /**
     * check if a scraping request is completed.
     *
     * @Route("/request/{id}/status", name="requestStatusCheck")
     */
    public function checkScrapingStatus($id, ScrapingRequestRepository $scrapingRequestRepo)
    {
        $request = $scrapingRequestRepo->find($id);

        return new JsonResponse([
            'completed' => $request->getIsCompleted(),
            'report_url' => $this->generateUrl('detailReport', ['id' => $id], UrlGeneratorInterface::ABSOLUTE_PATH),
        ]);
    }
}
