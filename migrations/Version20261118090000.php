<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Nouvelle offre « Visibilité locale : Google + IA », affichée en premier.
 * Pas de doublon si une offre du même titre existe déjà.
 */
final class Version20261118090000 extends AbstractMigration
{
    private const TITLE = 'Visibilité locale : Google + IA';

    public function getDescription(): string
    {
        return 'Offre Visibilité locale : Google + IA';
    }

    public function up(Schema $schema): void
    {
        $this->addSql(
            'INSERT INTO offer (title, summary, items, price_label, note, icon, position, active, updated_at)
             SELECT ?, ?, ?, ?, ?, ?, ?, true, NOW()
             WHERE NOT EXISTS (SELECT 1 FROM offer WHERE title = ?)',
            [
                self::TITLE,
                'Que l\'on vous trouve facilement, que l\'on cherche sur Google, sur Maps ou en posant la question à un assistant IA.',
                "Fiche Google complète et tenue à jour (horaires, photos, services)\n"
                . "Réponse à chaque avis sous 48 h\n"
                . "Vos infos identiques partout : site, Google, annuaires, réseaux\n"
                . "Site lisible par Google et par les IA (données structurées)\n"
                . 'Un point chaque mois sur ce qui a bougé',
                '69 €/mois',
                'Sans engagement. Aucun classement n\'est garanti : je m\'occupe de tout ce qui joue en votre faveur.',
                'bi-geo-alt',
                -1,
                self::TITLE,
            ]
        );
    }

    public function down(Schema $schema): void
    {
        $this->addSql('DELETE FROM offer WHERE title = ?', [self::TITLE]);
    }
}
