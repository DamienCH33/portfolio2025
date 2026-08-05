<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20260804000000 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Création de la table profile (titre / accroche / CV éditables depuis le back-office)';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('CREATE TABLE profile (id SERIAL NOT NULL, title VARCHAR(255) NOT NULL, subtitle VARCHAR(255) NOT NULL, intro TEXT NOT NULL, cv_file VARCHAR(255) DEFAULT NULL, github_url VARCHAR(255) DEFAULT NULL, linkedin_url VARCHAR(255) DEFAULT NULL, updated_at TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NULL, PRIMARY KEY(id))');
        $this->addSql('COMMENT ON COLUMN profile.updated_at IS \'(DC2Type:datetime_immutable)\'');

        // Ligne initiale reprenant le contenu actuel du site.
        $this->addSql(<<<'SQL'
            INSERT INTO profile (title, subtitle, intro, cv_file, github_url, linkedin_url, updated_at)
            VALUES (
                'Damien Chauveau',
                'Développeur Back-End PHP / Symfony',
                'Je conçois et développe des applications web en PHP avec Symfony, en transformant des idées en solutions concrètes et fonctionnelles. J''accorde une attention particulière à la structure des projets, à la qualité du code et à la maintenabilité des applications.',
                NULL,
                'https://github.com/DamienCH33',
                'https://www.linkedin.com/in/damien-chauveau-747892100/',
                NOW()
            )
        SQL);
    }

    public function down(Schema $schema): void
    {
        $this->addSql('DROP TABLE profile');
    }
}
