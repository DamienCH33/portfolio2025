<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Textes : « 9 ans » d'assurance dans l'intro du profil, et nombre de tests NutriPetit à jour.
 * Remet aussi le CV du dépôt (le fichier uploadé a été perdu avant le volume).
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
        // Nouvelle intro, seulement si l'ancienne (texte d'origine) est encore en place.
        $this->addSql("UPDATE profile SET intro = 'J''ai passé 9 ans dans l''assurance, à utiliser et à recetter des outils métier. Maintenant je les développe : API en Symfony, front en Angular, et la mise en prod avec.' WHERE intro LIKE '%du côté des utilisateurs, dans l''assurance%'");
        $this->addSql("UPDATE project SET outcome = REPLACE(outcome, '184 tests PHPUnit', 'Plus de 200 tests PHPUnit') WHERE outcome IS NOT NULL");
        // Le CV uploadé avant la mise en place du volume a disparu : retour au CV versionné.
        $this->addSql('UPDATE profile SET cv_file = NULL');
        // Captures d'écran à jour, versionnées dans public/image/projects.
        $this->addSql("UPDATE project SET image = 'nutripetit-accueil-2026-11.png' WHERE slug = 'nutripetit'");
        $this->addSql("UPDATE project SET image = 'monavispro-accueil-2026-11.png' WHERE slug = 'mon-avis-pro'");
    }

    public function down(Schema $schema): void
    {
        $this->addSql("UPDATE profile SET intro = REPLACE(intro, '9 ans du côté', 'près de dix ans du côté')");
        $this->addSql("UPDATE project SET outcome = REPLACE(outcome, 'Plus de 200 tests PHPUnit', '184 tests PHPUnit') WHERE outcome IS NOT NULL");
    }
}
