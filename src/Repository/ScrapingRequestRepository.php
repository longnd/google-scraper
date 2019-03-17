<?php

namespace App\Repository;

use App\Entity\ScrapingRequest;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method ScrapingRequest|null find($id, $lockMode = null, $lockVersion = null)
 * @method ScrapingRequest|null findOneBy(array $criteria, array $orderBy = null)
 * @method ScrapingRequest[]    findAll()
 * @method ScrapingRequest[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ScrapingRequestRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, ScrapingRequest::class);
    }

    /**
     * Get all scraping request which haven't been completed.
     */
    public function getPendingScrapingRequests()
    {
        return $this->createQueryBuilder('r')
            ->where('r.isCompleted = false')
            ->getQuery()
            ->execute();
    }

    /**
     * Get all scraping requests.
     */
    public function getAllScrapingRequest()
    {
        return $this->createQueryBuilder('r')
            ->orderBy('r.createdAt', 'DESC')
            ->getQuery()
            ->execute();
    }
}
