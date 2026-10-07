<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Le profil initial (Version20260804000000) affichait « Back-End PHP / Symfony ».
 * On aligne sur le CV : fullstack Symfony + Angular.
 * Ne touche qu'aux valeurs d'origine : un texte déjà modifié depuis le back-office est conservé.
 */
final class Version20261007090000 extends AbstractMigration
{
    private const OLD_SUBTITLE = 'Développeur Back-End PHP / Symfony';
    private const NEW_SUBTITLE = 'Développeur Fullstack PHP Symfony / Angular';

    private const OLD_INTRO = 'Je conçois et développe des applications web en PHP avec Symfony, en transformant des idées en solutions concrètes et fonctionnelles. J\'accorde une attention particulière à la structure des projets, à la qualité du code et à la maintenabilité des applications.';
    private const NEW_INTRO = 'Je conçois des API REST avec Symfony et les interfaces Angular qui les consomment, de la base de données au déploiement. Près de dix ans dans l\'assurance m\'ont appris à partir du besoin métier avant d\'écrire du code.';

    public function getDescription(): string
    {
        return 'Profil : passage du positionnement back-end au fullstack Symfony / Angular';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('UPDATE profile SET subtitle = ?, updated_at = NOW() WHERE subtitle = ?', [self::NEW_SUBTITLE, self::OLD_SUBTITLE]);
        $this->addSql('UPDATE profile SET intro = ?, updated_at = NOW() WHERE intro = ?', [self::NEW_INTRO, self::OLD_INTRO]);
    }

    public function down(Schema $schema): void
    {
        $this->addSql('UPDATE profile SET subtitle = ? WHERE subtitle = ?', [self::OLD_SUBTITLE, self::NEW_SUBTITLE]);
        $this->addSql('UPDATE profile SET intro = ? WHERE intro = ?', [self::OLD_INTRO, self::NEW_INTRO]);
    }
}
