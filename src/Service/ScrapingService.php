<?php
/**
 * This file is part of the application.
 *
 * (c) Long Nguyen <hello@longnd.me>
 *
 * Date: 2019-03-16
 * Time: 14:28
 */

namespace App\Service;

use App\Entity\ScrapingRequest;
use App\Entity\ScrapingResult;
use App\Repository\ScrapingRequestRepository;
use Doctrine\ORM\EntityManagerInterface;

class ScrapingService
{
    private $em;
    private $scrapingRequestRepo;

    public function __construct(
        EntityManagerInterface $em,
        ScrapingRequestRepository $scrapingRequestRepo)
    {
        $this->em = $em;
        $this->scrapingRequestRepo = $scrapingRequestRepo;
    }

    public function createScrapingRequest(array $keywords)
    {
        $request = new ScrapingRequest();
        foreach ($keywords as $keyword) {
            $result = new ScrapingResult();
            $result->setRequest($request)
                ->setKeyword($keyword);
            $this->em->persist($result);
        }

        $this->em->persist($request);
        $this->em->flush();
    }
}
