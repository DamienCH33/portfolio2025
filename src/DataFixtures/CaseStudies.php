<?php

namespace App\DataFixtures;

/**
 * Textes des études de cas, partagés entre la migration (prod) et les fixtures (dev).
 * Une ligne commençant par « - » s'affiche en puce ; une ligne vide sépare deux paragraphes.
 */
final class CaseStudies
{
    /** @return array<string, array{context: string, approach: string, challenge?: string, outcome: string}> clé = titre du projet */
    public static function all(): array
    {
        return [
            'NutriPetit' => [
                'context' => <<<'TXT'
En rayon, un parent n'a aucun outil fait pour son bébé. Le Nutri-Score et NOVA sont calculés pour des adultes : une compote très sucrée peut y paraître correcte, un lait infantile y est mal classé.

NutriPetit répond à une question simple : « ce produit est-il adapté à mon enfant de 0 à 3 ans ? ». On scanne le code-barres, on obtient une note sur 100, expliquée règle par règle et adaptée à l'âge.
TXT,
                'approach' => <<<'TXT'
- API Symfony 7 / PHP 8.4, PostgreSQL et Redis : toute la logique métier vit côté serveur, testable.
- Données produits issues d'Open Food Facts, scoring basé sur les recommandations OMS, ANSES, EFSA et le règlement UE 2016/127.
- Un moteur de règles déterministe : chaque risque est un « évaluateur » indépendant (risque d'étouffement, poissons contaminés, soja, arômes artificiels, apports en fer, oméga-3…). Une règle = une classe = des tests.
- L'IA est volontairement exclue du calcul : une note de santé doit être reproductible et vérifiable.
- Front Angular en PWA installable (scan caméra, historique, profil bébé), qui consomme l'API en JSON. La vitrine et l'administration restent en Twig pour le référencement.
- Caddy en point d'entrée unique : / pour la vitrine, /app pour l'Angular, /api pour l'API.
TXT,
                'challenge' => <<<'TXT'
Les laits infantiles ressortaient tous « au-dessus des seuils ». En comparant avec la table Ciqual de l'Anses, j'ai trouvé la cause : Open Food Facts stocke les valeurs de la poudre dans les champs censés contenir le lait reconstitué, soit environ 8 fois trop.

Plutôt que de bricoler un coefficient, j'ai sorti les laits du calcul nutriment par nutriment et écrit un calculateur dédié, basé sur ce qui distingue réellement deux laits : huile de palme, sucres ajoutés, prébiotiques, bio, protéines de soja. Chaque bonus et malus est rattaché à sa source réglementaire.

Deuxième difficulté, côté déploiement : le front et l'API sur deux domaines Railway différents bloquaient le cookie de session. J'ai réglé ça avec un reverse proxy : tout passe par un seul domaine, le cookie redevient first-party.
TXT,
                'outcome' => <<<'TXT'
- En ligne sur un seul domaine : vitrine, application Angular et API.
- 184 tests PHPUnit, PHPStan sans erreur, CI GitHub Actions à chaque push.
- Audit de sécurité passé : 10 points sur 12 corrigés, dont un jeton de session opaque contre les accès aux données d'un autre utilisateur.
TXT,
            ],
            'La compagnie des archers des Albères' => [
                'context' => <<<'TXT'
La Compagnie des Archers des Albères est un club de tir à l'arc associatif, fondé en 1991 à Sorède. Son ancien site était devenu difficile à maintenir : une interface vieillissante, et surtout aucune administration simple. Chaque actualité ou nouvelle photo demandait de passer par quelqu'un qui savait coder.

L'objectif : un site moderne que les bénévoles du club mettent à jour eux-mêmes, sans moi.
TXT,
                'approach' => <<<'TXT'
- Symfony 7.4 / PHP 8.4, PostgreSQL et Twig : un site vitrine rendu côté serveur, rapide et bien référencé, sans complexité inutile pour un club.
- Back-office EasyAdmin 5, au thème sombre aux couleurs du club : actualités par catégorie (podiums, événements, vie du club), albums photo, partenaires, horaires, tarifs, histoire du club et documents PDF (statuts, inscription, santé, règlement).
- Import groupé de photos pour la galerie : au retour d'une compétition, le club envoie toutes ses photos en une fois.
- Pages publiques pensées pour mobile : menu hamburger, slider d'accueil qui gère photos portrait et paysage avec un fond flou, partenaires en pied de page sur toutes les pages.
- Sécurité : rôles, limitation des tentatives de connexion, réinitialisation du mot de passe par e-mail en français.
TXT,
                'challenge' => <<<'TXT'
La mise en production sur Railway, où tout ce qui marchait en local a cassé l'un après l'autre.

- Les e-mails de réinitialisation de mot de passe ne partaient pas : Railway bloque le SMTP sortant. Je suis passé par l'API HTTP de Brevo, qui passe là où le SMTP est filtré.
- L'envoi de plusieurs photos d'un coup déconnectait l'administrateur. La cause était un simple avertissement PHP (limite max_file_uploads atteinte) envoyé avant les en-têtes HTTP, ce qui empêchait la session de s'enregistrer. J'ai fixé les limites d'upload dans l'image Docker (nombre de fichiers, taille, mémoire) pour que l'import groupé tienne la route.
- Les photos disparaissaient à chaque redéploiement : elles vivaient dans le conteneur. Je les ai déplacées sur un volume persistant Railway.
- Les liens générés sortaient en http derrière le proxy Railway : réglé en déclarant les proxies de confiance.

Chaque problème a donné un commit dédié et lisible, ce qui rendra le prochain déploiement beaucoup plus simple.
TXT,
                'outcome' => <<<'TXT'
- En ligne sur archers-des-alberes.fr, livré à un vrai client et administré par le club en autonomie.
- PHPStan niveau 7, une soixantaine de tests PHPUnit (fonctionnels, contrôleurs, dépôts), Rector et php-cs-fixer.
- CI GitHub Actions avec les tests exécutés sur une vraie base PostgreSQL.
TXT,
            ],
            'Cap Monta' => [
                'context' => <<<'TXT'
À Montalivet, au CHM et à Euronat, les bungalows et mobil-homes se louent surtout via des petites annonces. Le vacancier ne voit pas les dates libres, et le propriétaire gère les demandes à la main.

Cap Monta est un site de location pensé pour les deux : le vacancier cherche par dates et par nombre de voyageurs, voit les disponibilités réelles et envoie sa demande de réservation en ligne. Le propriétaire reçoit des demandes claires, sur des dates qu'il sait libres.
TXT,
                'approach' => <<<'TXT'
- Un parcours inspiré des grandes plateformes de location (recherche destination / dates / voyageurs, cartes d'annonces, fiche logement), avec une identité propre au lieu : la mer et la forêt, sans fioritures.
- API Symfony pour les logements, les disponibilités et les demandes de réservation ; front Angular pour une recherche et un calendrier fluides.
- PostgreSQL pour les données, Redis en cache, hébergement Railway.
- Fiche logement complète : calendrier des dates prises et libres, carte « Où se situe le logement », notation des logements.
- Une vraie photo en bandeau d'accueil plutôt qu'une illustration : un vacancier achète un lieu, pas un site.
TXT,
                'outcome' => <<<'TXT'
- En ligne et utilisé pour de vraies réservations.
- Un modèle économique simple : le site doit au minimum couvrir ses frais d'hébergement et de nom de domaine.
- Il sert de référence à mon offre « site avec réservation » pour les commerces et les loueurs.
TXT,
            ],
            'Mon Avis Pro' => [
                'context' => <<<'TXT'
Un commerçant sait que ses avis Google comptent, mais il n'a pas le temps de les surveiller ni d'y répondre. Un avis négatif sans réponse fait fuir les clients suivants.

MonAvisPro centralise les avis de l'établissement, prévient quand un avis négatif arrive et propose une réponse rédigée dans le ton du commerce, prête à publier.
TXT,
                'approach' => <<<'TXT'
- Symfony 7 / PHP 8.4 et PostgreSQL, déployé sur Railway avec FrankenPHP.
- Connexion OAuth 2.0 à Google Business Profile pour lire les avis et publier les réponses au nom du commerce.
- OpenAI pour proposer les réponses : l'IA rédige, le commerçant valide. Rien n'est publié sans lui.
- Architecture en services et DTO (synchronisation des avis, réponses, inscription) pour garder des contrôleurs minces et testables.
- Authentification hybride JWT et session, partagée entre l'API et l'interface.
TXT,
                'challenge' => <<<'TXT'
La sécurité. Une application qui détient les accès Google de commerçants ne peut pas se permettre d'approximation. J'ai fait passer un audit de sécurité Symfony sur le code et corrigé 10 des 12 points relevés.

Exemple concret : le jeton d'authentification était exposé dans une variable JavaScript globale, lisible par n'importe quel script injecté. Je l'ai supprimé au profit d'une session partagée en same-origin. J'ai aussi neutralisé les contenus d'avis avant affichage (XSS) et durci la politique de sécurité du contenu (CSP).
TXT,
                'outcome' => <<<'TXT'
- En ligne, avec un compte de démonstration.
- Plus de 70 % du code couvert par les tests PHPUnit, PHPStan niveau 7, CI GitHub Actions.
- Le moteur d'alertes et de bilans mensuels sert aussi à mon offre « Avis Google clé en main ».
TXT,
            ],
        ];
    }
}
