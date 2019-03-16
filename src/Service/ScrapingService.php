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
use Goutte\Client;

class ScrapingService
{
    const MIN_PAUSE_TIME = 4000000; // 4 seconds
    const MAX_PAUSE_TIME = 8000000; // 8 seconds
    const GOOGLE_DOMAIN = 'https://www.google.com/';
    const USER_AGENTS = [
        'Mozilla/5.0 (Windows NT 5.1; rv:2.0.1) Gecko/20100101 Firefox/4.0.1',
        'Mozilla/5.0 (Windows NT 5.1) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/41.0.2272.101 Safari/537.36',
        'Mozilla/5.0 (Windows NT 6.0) AppleWebKit/535.1 (KHTML, like Gecko) Chrome/13.0.782.112 Safari/535.1',
        'Mozilla/5.0 (Windows NT 6.1; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/40.0.2214.85 Safari/537.36',
    ];

    private $em;
    private $scrapingRequestRepo;
    private $client;

    public function __construct(
        EntityManagerInterface $em,
        ScrapingRequestRepository $scrapingRequestRepo)
    {
        $this->em = $em;
        $this->scrapingRequestRepo = $scrapingRequestRepo;
        $this->client = new Client();
        $this->setClientHeaders();
    }

    /**
     * Store the scraping request created by uploaded SCV file into DB.
     */
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

    /**
     * Set the neccessary headers for the Client before connecting to Google.
     */
    public function setClientHeaders()
    {
        $this->client->setHeader('Accept', 'text/xml,application/xml,application/xhtml+xml,text/html;q=0.9,text/plain;q=0.8,image/png,*/*;q=0.5')
            ->setHeader('Connection', 'keep-alive')
            ->setHeader('Keep-Alive', 115)
            ->setHeader('Accept-Charset', 'ISO-8859-1,utf-8;q=0.7,*;q=0.7')
            ->setHeader('Accept-Language', 'en-us,en;q=0.5')
            ->setHeader('Pragma', '')
        ;
    }

    /**
     * set a random user agent for the client.
     */
    public function rotateClientUserAgent()
    {
        $userAgent = array_rand(self::USER_AGENTS);
        $this->client->setHeader('user-agent', $userAgent);
    }

    /**
     * Put some random programmatic sleep calls in between requests.
     */
    private function pause()
    {
        usleep(rand(self::MIN_PAUSE_TIME, self::MAX_PAUSE_TIME));
    }

    /**
     * Init the request to Google, try to get to No country redirection version of the page.
     */
    private function initGoogle()
    {
        // Opening google.com may redirect to country specific site e.g. www.google.com.vn
        $this->client->request('GET', self::GOOGLE_DOMAIN);
        $this->pause();
        // Go back to google.com - No Country Redirection
        $this->client->request('GET', self::GOOGLE_DOMAIN.'/ncr');
    }

    /**
     * Extract data from Google search results.
     *
     * @param $keyword
     *
     * @return array
     */
    public function scrap($keyword)
    {
        $this->initGoogle();
        $crawler = $this->client->request('GET', sprintf('%s/search?q=%s', self::GOOGLE_DOMAIN, $keyword));

        $fullPageHtml = $crawler->html();
        $totalResults = $crawler->filter('#resultStats')->text();
        $linksCount = $crawler->filter('a')->count();
        $adWordsCount = $crawler->filter('.ads-visurl')->count();

        return [
            'html' => $fullPageHtml,
            'totalResults' => $totalResults,
            'linkCounts' => $linksCount,
            'adWordsCount' => $adWordsCount,
        ];
    }
}
