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
                'title' => 'Portfolio développeur Symfony',
                'description' => 'Portfolio personnel développé avec Symfony permettant de présenter mes projets, compétences et statistiques de visites via un dashboard administrateur.',
                'skills' => ['Symfony', 'PHP', 'Twig', 'Bootstrap', 'Docker', 'PostgreSQL'],
                'image' => 'image/projects/portfolio.png',
                'link' => 'https://github.com/DamienCH33/portfolio2025',
                'date' => '2026-03-01',
            ],

            [
                'title' => 'Critipixel',
                'description' => 'Application web permettant de publier et consulter des critiques de jeux vidéo avec gestion des utilisateurs.',
                'skills' => ['Symfony', 'PHP', 'Twig', 'MySQL', 'Bootstrap'],
                'image' => 'image/projects/critipixel.png',
                'link' => 'https://github.com/DamienCH33/critipixel',
                'date' => '2025-11-01',
            ],

            [
                'title' => 'Eco Garden',
                'description' => 'Site web dédié au jardinage écologique permettant de présenter des contenus et promouvoir des pratiques respectueuses de l’environnement.',
                'skills' => ['Symfony', 'PHP', 'HTML', 'CSS', 'JavaScript', 'MySQL'],
                'image' => 'image/projects/ecogarden.png',
                'link' => 'https://github.com/DamienCH33/Projet_Eco_Garden',
                'date' => '2025-09-01',
            ],

            [
                'title' => 'Ina Zaoui',
                'description' => 'Projet web réalisé dans le cadre de ma formation permettant de mettre en pratique les bases du développement web.',
                'skills' => ['Symfony', 'PHP', 'HTML', 'CSS', 'JavaScript', 'MySQL'],
                'image' => 'image/projects/inazaoui.png',
                'link' => 'https://github.com/DamienCH33/Projet-Ina-Zaoui',
                'date' => '2025-06-01',
            ],

            [
                'title' => 'Green Goodies',
                'description' => 'Application e-commerce développée dans le cadre du parcours OpenClassrooms permettant de gérer un catalogue de produits et des commandes.',
                'skills' => ['Symfony', 'PHP', 'Twig', 'Bootstrap', 'MySQL'],
                'image' => 'image/projects/greengoodies.png',
                'link' => 'https://github.com/DamienCH33/greengoodies',
                'date' => '2025-12-01',
            ],

            [
                'title' => 'AltaPro 65',
                'description' => 'Site web vitrine réalisé pour une entreprise d’élagage permettant de présenter ses services et faciliter la prise de contact.',
                'skills' => ['Symfony', 'PHP', 'HTML', 'CSS', 'JavaScript', 'Bootstrap'],
                'image' => 'image/projects/Altapro65.png',
                'link' => 'https://github.com/DamienCH33/Projet_AltaPro_65_V2',
                'date' => '2025-04-01',
            ],

            [
                'title' => 'TaskLinker',
                'description' => 'Plateforme de gestion de projets développée avec Symfony permettant de créer des projets et gérer des tâches collaboratives.',
                'skills' => ['Symfony', 'PHP', 'Twig', 'Bootstrap', 'MySQL'],
                'image' => 'image/projects/tasklinker.jpg',
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
