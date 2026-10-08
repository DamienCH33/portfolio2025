<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use App\DataFixtures\CaseStudies;
use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Étude de cas des Archers des Albères (écrite à partir du dépôt archersdesalberes-website).
 * Ne remplit que si l'étude de cas est encore vide : un texte saisi dans l'admin n'est pas écrasé.
 */
final class Version20261008120000 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Étude de cas : La compagnie des archers des Albères';
    }

    public function up(Schema $schema): void
    {
        $case = CaseStudies::all()['La compagnie des archers des Albères'];

        $this->addSql(
            'UPDATE project SET context = ?, approach = ?, challenge = ?, outcome = ? WHERE slug = ? AND context IS NULL',
            [$case['context'], $case['approach'], $case['challenge'] ?? null, $case['outcome'], 'la-compagnie-des-archers-des-alberes']
        );
    }

    public function down(Schema $schema): void
    {
        // Texte éditorial : pas de retour arrière automatique.
    }
}
