<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * - Ajoute project.logo (logo affiché dans « En ligne en ce moment » sur l'accueil).
 * - Corrige le texte de deux offres : plus de délai « 48 heures », QR code en PDF,
 *   alerte et bilan automatiques (MonAvisPro), mention « inclus dans les 35 €/mois ».
 * - Intro du profil alignée sur le nouveau hero.
 *   Un texte déjà modifié depuis le back-office n'est pas touché.
 */
final class Version20261007150000 extends AbstractMigration
{
    private const SITE_OLD = "Essentiel : 1 à 3 pages, contact, lien vers votre fiche Google — 650 €\nVitrine : jusqu'à 6 pages, galerie, actualités — 950 €\nAvec réservation ou annonces en ligne — dès 1 900 €\nHébergement, sauvegardes, sécurité et 30 min de modifications par mois";
    private const SITE_NEW = "Essentiel : 1 à 3 pages, contact, lien vers votre fiche Google — 650 €\nVitrine : jusqu'à 6 pages, galerie, actualités — 950 €\nAvec réservation ou annonces en ligne — dès 1 900 €\nHébergement, sauvegardes, sécurité et 30 min de modifications par mois — inclus dans les 35 €/mois";

    private const AVIS_OLD = "Une réponse personnalisée à chaque avis sous 48 heures, avec votre ton et votre signature\nUn QR code à poser en caisse pour obtenir plus d'avis\nUne alerte immédiate en cas d'avis négatif\nUn bilan chaque mois : nombre d'avis, note, réponses";
    private const AVIS_NEW = "Une réponse personnalisée à chaque avis, avec votre ton et votre signature\nUn QR code prêt à imprimer pour inviter vos clients à laisser un avis\nUne alerte automatique dès qu'un avis négatif arrive\nUn bilan automatique chaque mois : nombre d'avis, note moyenne, réponses";

    private const INTRO_PREV = 'Je conçois des API REST avec Symfony et les interfaces Angular qui les consomment, de la base de données au déploiement. Près de dix ans dans l\'assurance m\'ont appris à partir du besoin métier avant d\'écrire du code.';
    private const INTRO_NEW = 'J\'ai passé près de dix ans du côté des utilisateurs, dans l\'assurance. Aujourd\'hui je construis les outils : l\'API en Symfony, l\'écran en Angular, et la mise en ligne. Un seul interlocuteur, du premier échange au déploiement.';

    public function getDescription(): string
    {
        return 'Projet : champ logo. Offres : textes avis Google et site internet corrigés';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('ALTER TABLE project ADD logo VARCHAR(255) DEFAULT NULL');
        $this->addSql('UPDATE offer SET items = ?, updated_at = NOW() WHERE items = ?', [self::SITE_NEW, self::SITE_OLD]);
        $this->addSql('UPDATE offer SET items = ?, updated_at = NOW() WHERE items = ?', [self::AVIS_NEW, self::AVIS_OLD]);
        $this->addSql('UPDATE profile SET intro = ?, updated_at = NOW() WHERE intro = ?', [self::INTRO_NEW, self::INTRO_PREV]);
    }

    public function down(Schema $schema): void
    {
        $this->addSql('UPDATE profile SET intro = ? WHERE intro = ?', [self::INTRO_PREV, self::INTRO_NEW]);
        $this->addSql('UPDATE offer SET items = ? WHERE items = ?', [self::AVIS_OLD, self::AVIS_NEW]);
        $this->addSql('UPDATE offer SET items = ? WHERE items = ?', [self::SITE_OLD, self::SITE_NEW]);
        $this->addSql('ALTER TABLE project DROP logo');
    }
}
