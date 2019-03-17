<?php

namespace App\Controller;

use App\Repository\ScrapingRequestRepository;
use App\Repository\ScrapingResultRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Contracts\Translation\TranslatorInterface;

class ReportController extends AbstractController
{
    private $scrapingRequestRepo;
    private $scrapingResultRepo;
    private $translator;

    public function __construct(
        ScrapingRequestRepository $scrapingRequestRepo,
        ScrapingResultRepository $scrapingResultRepo,
        TranslatorInterface $translator
    ) {
        $this->scrapingRequestRepo = $scrapingRequestRepo;
        $this->scrapingResultRepo = $scrapingResultRepo;
        $this->translator = $translator;
    }

    /**
     * @Route("/reports", name="reports")
     */
    public function index()
    {
        $scrapingRequests = $this->scrapingRequestRepo->getAllScrapingRequest();

        return $this->render('report/general.html.twig', ['requests' => $scrapingRequests]);
    }

    /**
     * @Route("/reports/{id}", name="detailReport")
     */
    public function view($id)
    {
        if (!$scrapingRequest = $this->scrapingRequestRepo->find($id)) {
            throw $this->createNotFoundException($this->translator->trans('error.report_not_found'));
        }
        $scrapingResults = $this->scrapingResultRepo->getResults($scrapingRequest);

        return $this->render('report/detail.html.twig', ['results' => $scrapingResults]);
    }
}
