<?php

namespace App\DataFixtures;

use App\Entity\Skill;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class SkillFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $skills = [
            // --- Framework ---
            ['Symfony', 'Framework', 'https://cdn.jsdelivr.net/gh/devicons/devicon/icons/symfony/symfony-original.svg', 21],
            ['Twig', 'Framework', 'https://tertiaire.onlineformapro.com/wp-content/uploads/2018/11/twig_ezno_default-fi10224206x470.png', 19],
            ['Bootstrap', 'Framework', 'https://cdn.jsdelivr.net/gh/devicons/devicon/icons/bootstrap/bootstrap-original.svg', 18],
            ['Stimulus', 'Framework', 'https://avatars.githubusercontent.com/u/8641758?s=200&v=4', 17],
            ['ZXing', 'Framework', 'https://cdn.jsdelivr.net/gh/devicons/devicon/icons/java/java-original.svg', 16],

            // --- Langage ---
            ['PHP', 'Langage', 'https://cdn.jsdelivr.net/gh/devicons/devicon/icons/php/php-original.svg', 20],
            ['JavaScript', 'Langage', 'https://cdn.jsdelivr.net/gh/devicons/devicon/icons/javascript/javascript-original.svg', 5],
            ['TypeScript', 'Langage', 'https://cdn.jsdelivr.net/gh/devicons/devicon/icons/typescript/typescript-original.svg', 6],
            ['HTML', 'Langage', 'https://cdn.jsdelivr.net/gh/devicons/devicon/icons/html5/html5-original.svg', 4],
            ['CSS', 'Langage', 'https://cdn.jsdelivr.net/gh/devicons/devicon/icons/css3/css3-original.svg', 3],
            ['Angular', 'Langage', 'https://cdn.jsdelivr.net/gh/devicons/devicon/icons/angularjs/angularjs-original.svg', 7],

            // --- Architecture ---
            ['API REST', 'Architecture', 'https://www.iconpacks.net/icons/free-icons-6/free-rest-api-blue-logo-icon-22099-thumb.png', 17],
            ['Google Places API', 'Architecture', 'https://www.vectorlogo.zone/logos/google/google-icon.svg', 22],
            ['OpenAI API', 'Architecture', 'https://upload.wikimedia.org/wikipedia/commons/0/04/ChatGPT_logo.svg', 21],
            ['PWA', 'Architecture', 'https://cdn.jsdelivr.net/gh/devicons/devicon/icons/pwa/pwa-original.svg', 15],
            ['Open Food Facts API', 'Architecture', 'https://static.openfoodfacts.org/images/logos/off-logo-horizontal-light.svg', 14],
            ['Google Maps API', 'Architecture', 'https://www.vectorlogo.zone/logos/google_maps/google_maps-icon.svg', 13],

            // --- Base de données ---
            ['PostgreSQL', 'Base de données', 'https://cdn.jsdelivr.net/gh/devicons/devicon/icons/postgresql/postgresql-original.svg', 16],
            ['MySQL', 'Base de données', 'https://cdn.jsdelivr.net/gh/devicons/devicon/icons/mysql/mysql-original.svg', 15],
            ['Doctrine ORM', 'Base de données', 'https://w7.pngwing.com/pngs/198/984/png-transparent-doctrine-plain-wordmark-logo-icon.png', 14],
            ['Redis', 'Base de données', 'https://cdn.jsdelivr.net/gh/devicons/devicon/icons/redis/redis-original.svg', 13],

            // --- Outils /DevOps ---
            ['Docker', 'Outils /DevOps', 'https://cdn.jsdelivr.net/gh/devicons/devicon/icons/docker/docker-original.svg', 14],
            ['Git', 'Outils /DevOps', 'https://cdn.jsdelivr.net/gh/devicons/devicon/icons/git/git-original.svg', 13],
            ['GitHub', 'Outils /DevOps', 'https://cdn.jsdelivr.net/gh/devicons/devicon/icons/github/github-original.svg', 12],
            ['Composer', 'Outils /DevOps', 'https://cdn.jsdelivr.net/gh/devicons/devicon/icons/composer/composer-original.svg', 11],
            ['PhpStorm', 'Outils /DevOps', 'https://cdn.jsdelivr.net/gh/devicons/devicon/icons/phpstorm/phpstorm-original.svg', 10],
            ['VS Code', 'Outils /DevOps', 'https://cdn.jsdelivr.net/gh/devicons/devicon/icons/vscode/vscode-original.svg', 9],
            ['FrankenPHP', 'Outils /DevOps', 'https://frankenphp.dev/img/logo.png', 8],
            ['Railway', 'Outils /DevOps', 'https://railway.app/brand/logo-light.svg', 7],
            ['AssetMapper', 'Outils /DevOps', 'https://cdn.jsdelivr.net/gh/devicons/devicon/icons/symfony/symfony-original.svg', 6],

            // --- Qualité / Tests ---
            ['PHPUnit', 'Qualité / Tests', 'https://iconlogovector.com/uploads/images/2025/03/lg-67da9561d8a91-PHPUnit.webp', 8],
            ['PHPStan', 'Qualité / Tests', 'https://cdn.prod.website-files.com/6201296d337c6b1b479363bf/679767f238c6279808f934f6_PHPSTAN.png', 7],
            ['PHP-CS-Fixer', 'Qualité / Tests', 'https://images.icon-icons.com/2107/PNG/512/file_type_phpcsfixer_icon_130263.png', 6],
        ];

        foreach ($skills as [$name, $category, $logo, $priority]) {
            $skill = new Skill();

            $skill->setName($name);
            $skill->setCategory($category);
            $skill->setLogo($logo);
            $skill->setPriority($priority);

            $manager->persist($skill);
        }

        $manager->flush();
    }
}
