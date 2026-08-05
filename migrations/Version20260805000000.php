<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20260805000000 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Ajout du champ demo_url (lien de démo) sur project';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('ALTER TABLE project ADD demo_url VARCHAR(255) DEFAULT NULL');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('ALTER TABLE project DROP demo_url');
    }
}
