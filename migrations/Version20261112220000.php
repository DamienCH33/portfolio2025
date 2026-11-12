<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Textes : « 9 ans » d'assurance dans l'intro du profil, et nombre de tests NutriPetit à jour.
 * Simples remplacements : sans effet si le texte a déjà été modifié dans l'admin.
 */
final class Version20261112220000 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Textes : intro du profil (9 ans) et tests NutriPetit';
    }

    public function up(Schema $schema): void
    {
        $this->addSql("UPDATE profile SET intro = REPLACE(REPLACE(intro, 'près de dix ans', '9 ans'), 'Près de dix ans', '9 ans')");
        $this->addSql("UPDATE project SET outcome = REPLACE(outcome, '184 tests PHPUnit', 'Plus de 200 tests PHPUnit') WHERE outcome IS NOT NULL");
    }

    public function down(Schema $schema): void
    {
        $this->addSql("UPDATE profile SET intro = REPLACE(intro, '9 ans du côté', 'près de dix ans du côté')");
        $this->addSql("UPDATE project SET outcome = REPLACE(outcome, 'Plus de 200 tests PHPUnit', '184 tests PHPUnit') WHERE outcome IS NOT NULL");
    }
}
