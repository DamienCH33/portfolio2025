<?php

namespace App\DataFixtures;

use App\Entity\Project;
use App\Entity\Skill;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;

class ProjectFixtures extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager): void
    {
        $skillRepo = $manager->getRepository(Skill::class);

        $projects = [

            [
                'title' => 'Mon Avis Pro',
                'description' => 'MonAvisPro est une application web permettant aux commerçants et indépendants de surveiller 
                et gérer efficacement leur e-réputation sur Google. L’outil détecte automatiquement les nouveaux avis, alerte 
                en temps réel en cas d’avis négatif et permet de générer des réponses professionnelles optimisées grâce à l’intelligence
                artificielle. Il facilite ainsi la gestion des avis clients, améliore la réactivité et aide à préserver 
                l’image de marque des établissements.',
                'skills' => ['Symfony', 'PHP', 'API REST', 'PostgreSQL', 'Docker', 'PHPUnit', 'PHPStan', 'Git'],
                'image' => 'monavispro-69c68a9ee5974.png',
                'link' => 'https://github.com/DamienCH33/MonAvisPro',
                'date' => '2026-03-27',
            ],

            [
                'title' => 'Portfolio développeur Symfony',
                'description' => 'Portfolio personnel développé avec Symfony permettant de présenter mes projets, compétences et statistiques de visites via un dashboard administrateur.',
                'skills' => ['Symfony', 'PHP', 'Twig', 'PostgreSQL', 'Docker', 'PHPUnit', 'PHPStan', 'Git'],
                'image' => 'portfolio.png',
                'link' => 'https://github.com/DamienCH33/portfolio2025',
                'date' => '2025-12-01',
            ],

            [
                'title' => 'Critipixel',
                'description' => 'Mise en place d’une pipeline CI/CD avec GitHub Actions pour un projet Symfony : automatisation des tests, analyse statique du code (PHPStan, PHP CS Fixer) et gestion des migrations Doctrine pour préparer le déploiement.',
                'skills' => ['Symfony', 'PHP', 'PHPUnit', 'PHPStan', 'PHP-CS-Fixer', 'GitHub'],
                'image' => 'critipixel.png',
                'link' => 'https://github.com/DamienCH33/critipixel',
                'date' => '2026-01-01',
            ],

            [
                'title' => 'Eco Garden',
                'description' => 'Développement d’une API REST avec Symfony : gestion des ressources, authentification JWT, intégration d’une API externe avec cache et architecture backend sécurisée.',
                'skills' => ['Symfony', 'PHP', 'API REST', 'MySQL', 'Docker'],
                'image' => 'ecogarden.png',
                'link' => 'https://github.com/DamienCH33/Projet_Eco_Garden',
                'date' => '2026-03-01',
            ],

            [
                'title' => 'Ina Zaoui',
                'description' => 'Maintenance et évolution d’un site Symfony : migration de version, correction de bugs, optimisation des performances, ajout de fonctionnalités et mise en place de tests automatisés et CI/CD.',
                'skills' => ['Symfony', 'PHP', 'MySQL', 'PHPUnit', 'Git'],
                'image' => 'inazaoui.png',
                'link' => 'https://github.com/DamienCH33/Projet-Ina-Zaoui',
                'date' => '2025-12-02',
            ],

            [
                'title' => 'Green Goodies',
                'description' => 'Développement d’une application e-commerce avec Symfony : gestion du catalogue, authentification, commandes et implémentation d’une API REST sécurisée pour exposer les produits aux partenaires.',
                'skills' => ['Symfony', 'PHP', 'Twig', 'MySQL', 'API REST'],
                'image' => 'greengoodies.png',
                'link' => 'https://github.com/DamienCH33/greengoodies',
                'date' => '2026-02-01',
            ],

            [
                'title' => 'AltaPro 65',
                'description' => 'Site web vitrine réalisé pour une entreprise d’élagage permettant de présenter ses services et faciliter la prise de contact.',
                'skills' => ['Symfony', 'PHP', 'HTML', 'CSS'],
                'image' => 'Altapro65.jpg',
                'link' => 'https://github.com/DamienCH33/Projet_AltaPro_65_V2',
                'date' => '2025-04-01',
            ],

            [
                'title' => 'TaskLinker',
                'description' => 'Plateforme de gestion de projets développée avec Symfony permettant de créer des projets et gérer des tâches collaboratives.',
                'skills' => ['Symfony', 'PHP', 'Twig', 'MySQL'],
                'image' => 'tasklinker.jpg',
                'link' => 'https://github.com/DamienCH33/projet_TaskLinker',
                'date' => '2025-10-01',
            ],
        ];

        foreach ($projects as $data) {
            $project = new Project();

            $project->setTitle($data['title']);
            $project->setDescription($data['description']);
            $project->setImage($data['image']);
            $project->setLink($data['link']);
            $project->setCreatedAt(new \DateTimeImmutable($data['date']));

            foreach ($data['skills'] as $skillName) {
                $skill = $skillRepo->findOneBy(['name' => $skillName]);

                if ($skill) {
                    $project->addTechStack($skill);
                }
            }

            $manager->persist($project);
        }

        $manager->flush();
    }

    public function getDependencies(): array
    {
        return [
            SkillFixtures::class,
        ];
    }
}
