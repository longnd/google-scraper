<?php

namespace App\Controller;

use App\Repository\ScrapingRequestRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class ReportController extends AbstractController
{
    /**
     * @Route("/reports", name="reports")
     */
    public function index(ScrapingRequestRepository $scrapingRequestRepo)
    {
        $scrapingRequests = $scrapingRequestRepo->getAllScrapingRequest();

        return $this->render('report/general.html.twig', ['requests' => $scrapingRequests]);
    }
}
