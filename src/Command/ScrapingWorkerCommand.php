<?php

namespace App\Command;

use App\Entity\ScrapingRequest;
use App\Repository\ScrapingRequestRepository;
use App\Repository\ScrapingResultRepository;
use App\Service\ScrapingService;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

class ScrapingWorkerCommand extends Command
{
    protected static $defaultName = 'app:scraper';
    private $em;
    private $scrapingRequestRepo;
    private $scrapingResultRepo;
    private $scrapingService;

    protected function configure()
    {
        $this
            ->setDescription('A scraper to extract Google search result')
            ->addArgument('keyword', InputArgument::OPTIONAL, 'keyword')
        ;
    }

    public function __construct(
        ScrapingRequestRepository $scrapingRequestRepo,
        ScrapingResultRepository $scrapingResultRepo,
        ScrapingService $scrapingService)
    {
        $this->scrapingRequestRepo = $scrapingRequestRepo;
        $this->scrapingResultRepo = $scrapingResultRepo;
        $this->scrapingService = $scrapingService;

        parent::__construct();
    }

    /**
     * execute the command.
     *
     * @return int|void|null
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $io = new SymfonyStyle($input, $output);
        $keyword = $input->getArgument('keyword');

        if ($keyword) {
            $io->note(sprintf('Start extracting result for keyword: %s', $keyword));

            $result = $this->scrapingService->scrap($keyword);

            $io->writeln(sprintf(
                "AdWords Advertisers: <info>%s</info>\nLinks: <info>%s</info>\nResult stats: <info>%s</info>",
                $result['adWordsCount'], $result['linkCounts'], $result['resultStats']
            ));

            return;
        }

        $scrapingRequests = $this->scrapingRequestRepo->getPendingScrapingRequests();

        /** @var ScrapingRequest $request */
        foreach ($scrapingRequests as $request) {
            $this->handleScrapingRequest($request);
        }
    }

    /**
     * process a scraping request - extract google search results for all of the keywords.
     */
    private function handleScrapingRequest(ScrapingRequest $request)
    {
        foreach ($request->getResults() as $result) {
            if (empty($result->getHtml())) {
                $extractedData = $this->scrapingService->scrap($result->getKeyword());

                $this->scrapingService->updateResult($result, $extractedData);
            }
        }

        $this->scrapingService->markScrapingRequestCompleted($request);
    }
}
