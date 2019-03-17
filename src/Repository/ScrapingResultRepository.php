<?php

namespace App\Repository;

use App\Entity\ScrapingResult;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method ScrapingResult|null find($id, $lockMode = null, $lockVersion = null)
 * @method ScrapingResult|null findOneBy(array $criteria, array $orderBy = null)
 * @method ScrapingResult[]    findAll()
 * @method ScrapingResult[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ScrapingResultRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, ScrapingResult::class);
    }
}
