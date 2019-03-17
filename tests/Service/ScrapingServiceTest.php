<?php
/**
 * This file is part of the application.
 *
 * (c) Long Nguyen <hello@longnd.me>
 *
 * Date: 2019-03-17
 * Time: 21:29
 */

namespace App\Tests\Service;

use App\Repository\ScrapingRequestRepository;
use App\Service\ScrapingService;
use Doctrine\ORM\EntityManagerInterface;
use PHPUnit\Framework\TestCase;

class ScrapingServiceTest extends TestCase
{
    /** @var ScrapingService */
    private $scrapingService;

    public function setup()
    {
        $entityManger = $this->createMock(EntityManagerInterface::class);
        $scrapingRepo = $this->createMock(ScrapingRequestRepository::class);

        $this->scrapingService = new ScrapingService($entityManger, $scrapingRepo);
    }

    public function testScrap()
    {
        $result = $this->scrapingService->scrap('hello');

        $keys = array_keys($result);

        $this->assertContains('html', $keys);
        $this->assertContains('adWordsCount', $keys);
        $this->assertContains('linkCounts', $keys);
        $this->assertContains('resultStats', $keys);
    }
}
