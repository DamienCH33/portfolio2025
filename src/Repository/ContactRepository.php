<?php

// src/Repository/ContactRepository.php

namespace App\Repository;

use App\Entity\Contact;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

class ContactRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Contact::class);
    }

    public function getMonthlyCounts(int $year): array
    {
        return $this->createQueryBuilder('c')
            ->select('MONTH(c.createdAt) as month, COUNT(c.id) as count')
            ->where('YEAR(c.createdAt) = :year')
            ->setParameter('year', $year)
            ->groupBy('month')
            ->getQuery()
            ->getResult();
    }
}
