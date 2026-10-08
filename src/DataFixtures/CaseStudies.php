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
            'Cap Monta' => [
                'context' => <<<'TXT'
Au CHM Montalivet et à Euronat, les bungalows, mobil-homes et chalets se louent entre particuliers via des sites d'annonces où les disponibilités sont écrites à la main : « 19/07 au 22/08 non disponible ». Pour trouver une semaine libre en août, il faut lire chaque annonce, écrire à plusieurs propriétaires et attendre.

Cap Monta fait l'inverse : chaque logement a un vrai calendrier, les semaines libres s'affichent directement dans les résultats, et le locataire envoie sa demande au propriétaire, qui répond sous 48 heures. Pas de commission, pas de paiement sur le site.
TXT,
                'approach' => <<<'TXT'
- API Symfony 8.1 et API Platform 4, front Angular 22 en rendu serveur (SSR) pour le référencement, en 4 langues (français, anglais, néerlandais, allemand) : la clientèle du CHM est très européenne.
- PostgreSQL pour tout ce qui décide, Redis uniquement pour le confort (cache du calendrier) : Redis coupé, le site continue.
- Une demande de séjour suit une machine à états (Symfony Workflow) : envoyée, acceptée, refusée, expirée ou annulée, avec relance du propriétaire à 24 h et expiration automatique à 48 h.
- Import d'annonce par IA (Mistral) : le propriétaire colle son texte, l'IA en tire le logement, les tarifs et les dates prises, il vérifie avant de publier. L'IA assiste, elle ne décide pas.
- Données personnelles : suivi d'une demande sans compte, coordonnées du locataire visibles seulement après acceptation, purge automatique.
- 34 décisions d'architecture documentées avec leur contexte et leur coût.
TXT,
                'challenge' => <<<'TXT'
Le piège classique d'un moteur de réservation maison : deux personnes demandent les mêmes dates au même moment, le code vérifie la disponibilité pour chacune, voit « libre » deux fois, et enregistre deux séjours qui se chevauchent.

Plutôt que de compter sur le code, j'ai confié la garantie à PostgreSQL : une contrainte d'exclusion sur la période du séjour (arrivée incluse, départ exclu, pour que le samedi du départ reste libre pour l'arrivée suivante). La base refuse elle-même deux séjours qui se chevauchent, quelle que soit la course entre les requêtes ; un verrou Redis transforme simplement ce cas en message d'erreur propre.

Difficulté dans la difficulté : Doctrine ne connaît pas ce type de contrainte et propose de la supprimer à chaque nouvelle migration. J'ai ajouté un test d'intégration qui vérifie sa présence en base : si une migration l'efface, la CI casse.

Deuxième chantier, l'import par IA : un tarif inventé publié au nom d'un propriétaire serait grave. J'ai écrit le jeu d'évaluation avant l'agent (de vraies annonces avec la réponse attendue), avec une règle dure vérifiée en PHP : aucun prix absent du texte. Résultat : zéro prix inventé, et un score mesuré honnêtement sur des annonces jamais vues.
TXT,
                'outcome' => <<<'TXT'
- En ligne, sur un seul domaine : l'API n'est joignable que par le réseau privé, via le serveur du front.
- 324 tests côté API (unitaires, intégration sur vrai PostgreSQL) et 103 côté web, PHPStan niveau 7, TypeScript strict, CI GitHub Actions.
- PageSpeed : 100 sur ordinateur, 93 en performance mobile.
- Audit de sécurité de la production : 83 contrôles, 0 échec.
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
