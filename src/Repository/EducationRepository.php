<?php

namespace App\Repository;

use App\Entity\Education;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Education>
 */
class EducationRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Education::class);
    }

    /**
     * @return Education[]
     */
    public function findOrdered(): array
    {
        return $this->createQueryBuilder('e')
            ->addSelect('(CASE WHEN e.yearEnd IS NULL THEN 1 ELSE 0 END) AS HIDDEN inProgress')
            ->orderBy('inProgress', 'DESC')
            ->addOrderBy('e.yearEnd', 'DESC')
            ->addOrderBy('e.yearStart', 'DESC')
            ->getQuery()
            ->getResult();
    }
}
