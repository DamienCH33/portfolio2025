<?php

namespace App\DataFixtures;

use App\Entity\Education;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class EducationFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $education = new Education();

        $education->setInstitution('OpenClassrooms');
        $education->setTitle('Développeur Concepteur Logiciel (RNCP niveau 6) – Spécialisation PHP Symfony');
        $education->setYearStart(2025);
        $education->setYearEnd(2026);

        $manager->persist($education);

        $manager->flush();
    }
}
