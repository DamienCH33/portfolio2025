<?php

namespace App\DataFixtures;

use App\Entity\Profile;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

/**
 * Le profil est normalement créé par les migrations ; `doctrine:fixtures:load` vide la base,
 * donc on le recrée ici pour que le site reste complet après un chargement de fixtures.
 */
class ProfileFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $profile = (new Profile())
            ->setTitle('Damien Chauveau')
            ->setSubtitle('Développeur Fullstack PHP Symfony / Angular')
            ->setIntro('J\'ai passé 9 ans dans l\'assurance, à utiliser et à recetter des outils métier. Maintenant je les développe : API en Symfony, front en Angular, et la mise en prod avec.')
            ->setGithubUrl('https://github.com/DamienCH33')
            ->setLinkedinUrl('https://www.linkedin.com/in/chauveau-damien/')
            ->setUpdatedAt(new \DateTimeImmutable());

        $manager->persist($profile);
        $manager->flush();
    }
}
