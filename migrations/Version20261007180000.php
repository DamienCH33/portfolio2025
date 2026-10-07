<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use App\DataFixtures\CaseStudies;
use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;
use Symfony\Component\String\Slugger\AsciiSlugger;

/**
 * Pages projet détaillées : slug + 4 blocs d'étude de cas + captures supplémentaires.
 * Remplit les slugs de tous les projets existants, et les études de cas de NutriPetit
 * et Mon Avis Pro (seulement si elles sont encore vides).
 */
final class Version20261007180000 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Projets : slug, étude de cas (besoin, choix, difficulté, résultat), galerie';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('ALTER TABLE project ADD slug VARCHAR(160) DEFAULT NULL');
        $this->addSql('ALTER TABLE project ADD context TEXT DEFAULT NULL');
        $this->addSql('ALTER TABLE project ADD approach TEXT DEFAULT NULL');
        $this->addSql('ALTER TABLE project ADD challenge TEXT DEFAULT NULL');
        $this->addSql('ALTER TABLE project ADD outcome TEXT DEFAULT NULL');
        $this->addSql("ALTER TABLE project ADD gallery JSON DEFAULT '[]' NOT NULL");
        $this->addSql('CREATE UNIQUE INDEX UNIQ_2FB3D0EE989D9B62 ON project (slug)');

        $slugger = new AsciiSlugger('fr');
        $used = [];
        foreach ($this->connection->fetchAllAssociative('SELECT id, title FROM project ORDER BY id') as $row) {
            $base = strtolower((string) $slugger->slug((string) $row['title']));
            $slug = $base;
            $i = 2;
            while (isset($used[$slug])) {
                $slug = $base . '-' . $i++;
            }
            $used[$slug] = true;
            $this->addSql('UPDATE project SET slug = ? WHERE id = ?', [$slug, $row['id']]);
        }

        // Lien LinkedIn définitif
        $this->addSql("UPDATE profile SET linkedin_url = 'https://www.linkedin.com/in/chauveau-damien/' WHERE linkedin_url LIKE '%damien-chauveau-747892100%'");

        foreach (CaseStudies::all() as $title => $case) {
            $this->addSql(
                'UPDATE project SET context = ?, approach = ?, challenge = ?, outcome = ? WHERE title = ? AND context IS NULL',
                [$case['context'], $case['approach'], $case['challenge'] ?? null, $case['outcome'], $title]
            );
        }
    }

    public function down(Schema $schema): void
    {
        $this->addSql('DROP INDEX UNIQ_2FB3D0EE989D9B62');
        $this->addSql('ALTER TABLE project DROP slug, DROP context, DROP approach, DROP challenge, DROP outcome, DROP gallery');
    }
}
