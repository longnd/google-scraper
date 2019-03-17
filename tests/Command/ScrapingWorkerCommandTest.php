<?php

namespace App\Tests\Command;

use App\Command\ScrapingWorkerCommand;
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
    }

    /**
     * Test command with the case that user explicitly provide a keyword.
     */
    public function testProvidedKeyword()
    {
        $command = new ScrapingWorkerCommand(
            $this->scrapingRequestRepo,
            $this->scrapingResultRepo,
            $this->scrapingService
        );

        $application = new Application();
        $application->add($command);

        $commandTester = new CommandTester($command);
        $commandTester->execute([
            'command' => $command->getName(),
            'keyword' => 'john doe',
        ]);

        $output = $commandTester->getDisplay();
        $this->assertContains('Start extracting result for keyword', $output);
        $this->assertContains('AdWords Advertisers', $output);
        $this->assertContains('Links:', $output);
        $this->assertContains('Result stats', $output);
    }
}
