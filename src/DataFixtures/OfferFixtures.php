<?php

namespace App\DataFixtures;

use App\Entity\Offer;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

/**
 * Offres de la page Services (mêmes contenus que les migrations), recréées après un
 * `doctrine:fixtures:load` qui vide la base.
 */
class OfferFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $offers = [
            [
                'Site internet',
                'Un site rapide, à votre image, que vous mettez à jour vous-même.',
                "Essentiel : 1 à 3 pages, contact, lien vers votre fiche Google — 650 €\nVitrine : jusqu'à 6 pages, galerie, actualités — 950 €\nAvec réservation ou annonces en ligne — dès 1 900 €\nHébergement, sauvegardes, sécurité et 30 min de modifications par mois — inclus dans les 35 €/mois",
                'dès 650 € + 35 € par mois',
                'Associations : 30 € par mois',
                'bi-window',
            ],
            [
                'Avis Google clé en main',
                'Vos clients lisent vos avis, et surtout vos réponses. Je m\'en occupe.',
                "Une réponse personnalisée à chaque avis, avec votre ton et votre signature\nUn QR code prêt à imprimer pour inviter vos clients à laisser un avis\nUne alerte automatique dès qu'un avis négatif arrive\nUn bilan automatique chaque mois : nombre d'avis, note moyenne, réponses",
                '29 € par mois',
                'Vous restez propriétaire de votre fiche et pouvez me retirer l\'accès en un clic.',
                'bi-star-half',
            ],
            [
                'Fiche Google entretenue',
                'Une fiche à jour, c\'est plus de clients qui vous trouvent sur Google Maps.',
                "Horaires des jours fériés et des vacances, toujours justes\n2 publications par mois : nouveautés, événements, offres\nPhotos, description et catégories optimisées",
                '39 € par mois',
                'Avec les avis Google : 59 € par mois',
                'bi-geo-alt',
            ],
            [
                'Maintenance de votre site WordPress',
                'Votre site a été fait par quelqu\'un qui n\'est plus là ? Je le reprends en main.',
                "Mises à jour de WordPress et des extensions, sans casse\nSauvegardes automatiques et surveillance de la sécurité\n30 minutes de modifications chaque mois",
                '39 € par mois',
                'Bilan de départ : 90 €',
                'bi-shield-check',
            ],
        ];

        foreach ($offers as $i => [$title, $summary, $items, $price, $note, $icon]) {
            $manager->persist(
                (new Offer())
                    ->setTitle($title)
                    ->setSummary($summary)
                    ->setItems($items)
                    ->setPriceLabel($price)
                    ->setNote($note)
                    ->setIcon($icon)
                    ->setPosition(($i + 1) * 10)
                    ->setActive(true)
            );
        }

        $manager->flush();
    }
}
