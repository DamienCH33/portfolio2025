<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use App\DataFixtures\CaseStudies;
use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Étude de cas Cap Monta réécrite à partir du dépôt (README + ADR) : la version précédente
 * mentionnait une carte et une notation qui n'existent pas. Ne remplace que le texte
 * d'origine : une étude de cas déjà modifiée dans l'admin n'est pas touchée.
 */
final class Version20261008100000 extends AbstractMigration
{
    private const OLD_CONTEXT_START = 'À Montalivet, au CHM et à Euronat, les bungalows et mobil-homes se louent surtout via des petites annonces.%';

    public function getDescription(): string
    {
        return 'Étude de cas Cap Monta : texte réécrit (difficulté ajoutée, fonctionnalités corrigées)';
    }

    public function up(Schema $schema): void
    {
        $case = CaseStudies::all()['Cap Monta'];

        $this->addSql(
            'UPDATE project SET context = ?, approach = ?, challenge = ?, outcome = ? WHERE title = ? AND context LIKE ?',
            [$case['context'], $case['approach'], $case['challenge'] ?? null, $case['outcome'], 'Cap Monta', self::OLD_CONTEXT_START]
        );
    }

    public function down(Schema $schema): void
    {
        // Texte éditorial : pas de retour arrière automatique.
    }
}
