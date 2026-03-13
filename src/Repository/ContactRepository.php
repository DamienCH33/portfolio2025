<?php
// src/Repository/ContactRepository.php
namespace App\Repository;

use App\Entity\Contact;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Contact>
 */
class ContactRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Contact::class);
    }

    /**
     * @return array<int, array{month: int, count: int}>
     */
    public function getMonthlyCounts(int $year): array
    {
        return $this->getEntityManager()->getConnection()->executeQuery(
            '
        SELECT EXTRACT(MONTH FROM created_at) AS month, COUNT(id) AS count
        FROM contact
        WHERE EXTRACT(YEAR FROM created_at) = :year
        GROUP BY month
        ORDER BY month
        ',
            ['year' => $year]
        )->fetchAllAssociative();
    }
}
