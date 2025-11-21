<?php

namespace App\Repository;

use App\Entity\Visit;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

class VisitRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Visit::class);
    }

    public function getMonthlyCounts(int $year): array
    {
        return $this->createQueryBuilder('v')
            ->select('MONTH(v.createdAt) AS month, COUNT(v.id) AS count')
            ->where('YEAR(v.createdAt) = :year')
            ->setParameter('year', $year)
            ->groupBy('month')
            ->getQuery()
            ->getResult();
    }
}
