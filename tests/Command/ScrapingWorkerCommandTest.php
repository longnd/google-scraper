<?php

namespace App\Tests\Command;

use App\Command\ScrapingWorkerCommand;
use App\Entity\ScrapingRequest;
use App\Entity\ScrapingResult;
use App\Repository\ScrapingRequestRepository;
use App\Repository\ScrapingResultRepository;
use App\Service\ScrapingService;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\Console\Application;
use Symfony\Component\Console\Tester\CommandTester;

class ScrapingWorkerCommandTest extends KernelTestCase
{
    private $scrapingRequestRepo;
    private $scrapingResultRepo;
    private $scrapingService;
    private $command;

    public function setup()
    {
        $this->scrapingRequestRepo = $this->createMock(ScrapingRequestRepository::class);
        $this->scrapingResultRepo = $this->createMock(ScrapingResultRepository::class);
        $this->scrapingService = $this->createMock(ScrapingService::class);

        $scrapingResultsMap = [
            ['john doe', [
                    'html' => '<html></html>',
                    'adWordsCount' => 2,
                    'linkCounts' => 3,
                    'resultStats' => 'About 1000 results',
                ],
            ],
        ];

        $this->scrapingService->expects($this->any())
            ->method('scrap')
            ->will($this->returnValueMap($scrapingResultsMap));

        $this->command = new ScrapingWorkerCommand(
            $this->scrapingRequestRepo,
            $this->scrapingResultRepo,
            $this->scrapingService
        );

        $application = new Application();
        $application->add($this->command);
    }

    /**
     * Test command with the case that user explicitly provide a keyword.
     */
    public function testProvidedKeyword()
    {
        $commandTester = new CommandTester($this->command);
        $commandTester->execute([
            'command' => $this->command->getName(),
            'keyword' => 'john doe',
        ]);

        $output = $commandTester->getDisplay();
        $this->assertContains('Start extracting result for keyword', $output);
        $this->assertContains('AdWords Advertisers', $output);
        $this->assertContains('Links:', $output);
        $this->assertContains('Result stats', $output);
    }

    /**
     * Test the worker - no particular keyword provided - the worker just look into DB
     * for pending scraping request and do the crawling using their uploaded keywords.
     *
     * @dataProvider workerDataProvider
     */
    public function testWorker($scrapingRequest)
    {
        $this->scrapingRequestRepo->expects($this->any())
            ->method('getPendingScrapingRequests')
            ->willReturn([$scrapingRequest]);

        $commandTester = new CommandTester($this->command);
        $commandTester->execute([
            'command' => $this->command->getName(),
        ]);

        $output = $commandTester->getDisplay();
        $this->assertContains('Start processing request', $output);
        $this->assertContains('Keyword', $output);
    }

    public function workerDataProvider()
    {
        $scrapingRequest = new ScrapingRequest();
        $scrapingRequest->setIsCompleted(false);

        $result = new ScrapingResult();
        $result->setKeyword('john doe');

        $scrapingRequest->addResult($result);

        return [
            [$scrapingRequest],
        ];
    }
}
