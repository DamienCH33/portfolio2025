<?php

namespace App\Tests\Repository;

use App\Repository\VisitRepository;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class VisitRepositoryTest extends KernelTestCase
{
    public function testMonthlyCountsDoesNotCrash(): void
    {
        self::bootKernel();

        $repo = static::getContainer()->get(VisitRepository::class);

        $result = $repo->getMonthlyCounts((int) date('Y'));

        $this->assertIsArray($result);
    }
}
