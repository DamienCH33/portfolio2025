<?php

namespace App\Repository;

use App\Entity\Offer;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Offer>
 */
class OfferRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Offer::class);
    }

    /** @return list<Offer> */
    public function findPublished(): array
    {
        /** @var list<Offer> $offers */
        $offers = $this->findBy(['active' => true], ['position' => 'ASC', 'id' => 'ASC']);

        return $offers;
    }

    /** @return list<Offer> */
    public function findAllOrdered(): array
    {
        /** @var list<Offer> $offers */
        $offers = $this->findBy([], ['position' => 'ASC', 'id' => 'ASC']);

        return $offers;
    }
}
