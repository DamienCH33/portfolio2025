<?php

namespace App\Repository;

use App\Entity\Visit;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Visit>
 */
class VisitRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Visit::class);
    }

    /**
     * @return array<int, array{month:int, count:int}>
     */
    public function getMonthlyCounts(int $year): array
    {
        return $this->getEntityManager()->getConnection()->executeQuery(
            '
        SELECT EXTRACT(MONTH FROM created_at) AS month, COUNT(id) AS count
        FROM visit
        WHERE EXTRACT(YEAR FROM created_at) = :year
        GROUP BY month
        ORDER BY month
        ',
            ['year' => $year]
        )->fetchAllAssociative();
    }

    public function countToday(): int
    {
        $start = new \DateTimeImmutable('today');

        return (int) $this->createQueryBuilder('v')
            ->select('COUNT(v.id)')
            ->where('v.createdAt >= :start')
            ->setParameter('start', $start)
            ->getQuery()
            ->getSingleScalarResult();
    }
}
