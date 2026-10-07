# Portfolio — Damien Chauveau

[![Symfony CI](https://github.com/DamienCH33/portfolio2025/actions/workflows/ci.yml/badge.svg)](https://github.com/DamienCH33/portfolio2025/actions/workflows/ci.yml)
![PHP 8.4](https://img.shields.io/badge/PHP-8.4-777BB4?logo=php&logoColor=white)
![Symfony 7.3](https://img.shields.io/badge/Symfony-7.3-000000?logo=symfony)
![PostgreSQL](https://img.shields.io/badge/PostgreSQL-16-4169E1?logo=postgresql&logoColor=white)

Site personnel de développeur fullstack **PHP / Symfony & Angular**, en ligne sur **[damienchauveau-dev.fr](https://www.damienchauveau-dev.fr)**.

Ce n'est pas une page statique : tout le contenu (profil, projets et études de cas, compétences, offres de services) est géré depuis un **back-office maison**, et le site sert aussi de vitrine à mon activité freelance.

---

## Ce que fait le site

**Côté public**
- Accueil avec le panneau « En ligne en ce moment » : les projets qui ont un lien de démo, avec leur logo.
- **Pages projet détaillées** (`/projets/{slug}`) : le besoin, les choix techniques, la difficulté rencontrée, le résultat, et des captures.
- Page **Services** : offres freelance pour commerces et associations, lues en base.
- Formulaire de contact avec **notification e-mail** à l'administrateur (répondre-à = l'expéditeur).
- SEO : `sitemap.xml` généré dynamiquement (pages projet incluses), `robots.txt`, balises Open Graph, URL canonique.

**Back-office (`/admin`, rôle `ROLE_ADMIN`)**
- Tableau de bord : visites et messages du mois, graphique d'activité (Chart.js).
- CRUD : profil, compétences, projets (capture, logo, galerie, étude de cas), diplômes, **offres de services**.
- Outil **affichette QR « avis Google »** : génère un A5 imprimable / PDF pour les clients de l'offre avis.

---

## Stack

| | |
|---|---|
| Back-end | PHP 8.4, Symfony 7.3, Doctrine ORM, Twig |
| Base de données | PostgreSQL 16 (Docker en local) |
| Front | Twig, CSS maison (thème sombre, dégradé vert/cyan), Bootstrap 5 pour la grille et les formulaires |
| E-mails | Symfony Mailer, envoi synchrone (pas de worker à faire tourner) |
| Qualité | PHPUnit (unitaires, intégration, fonctionnels), PHPStan, PHP-CS-Fixer |
| CI / déploiement | GitHub Actions à chaque push et PR, Railway |

## Sécurité

- `/admin` réservé à `ROLE_ADMIN` (access_control + `#[IsGranted]` sur chaque contrôleur).
- **Anti force brute** sur la connexion : 5 essais par minute (`login_throttling`).
- Formulaire de contact : jeton CSRF, champ piège anti-robot, délai minimal de saisie, limite de 5 envois par minute et par IP.
- Uploads contrôlés sur le type réel du fichier (PNG, JPG, WebP), noms de fichiers régénérés.
- Statistiques de visites **sans cookie ni adresse IP** stockée.

---

## Installation en local

Prérequis : PHP 8.4, Composer, Docker, [Symfony CLI](https://symfony.com/download) (facultatif).

```bash
git clone https://github.com/DamienCH33/portfolio2025.git
cd portfolio2025
composer install
cp .env .env.local          # puis renseigner DATABASE_URL et les variables ci-dessous
docker compose --env-file .env.local up -d database
php bin/console doctrine:migrations:migrate
php bin/console doctrine:fixtures:load        # données de démo (vide la base)
php bin/console app:create-admin admin@example.com MotDePasseSolide
symfony serve                                 # ou : php -S 127.0.0.1:8000 -t public
```

### Variables d'environnement

| Variable | Rôle |
|---|---|
| `DATABASE_URL` | Connexion PostgreSQL |
| `MAILER_DSN` | Fournisseur d'envoi (ex. `brevo+api://CLE@default`) ; `null://null` = aucun envoi |
| `CONTACT_RECIPIENT` | Adresse qui reçoit les messages du formulaire |
| `MAILER_FROM` | Expéditeur autorisé chez le fournisseur |

Si `docker compose` lance la base avec d'autres identifiants que ceux de `DATABASE_URL`, ajouter `POSTGRES_USER`, `POSTGRES_PASSWORD` et `POSTGRES_DB` dans `.env.local` (ils sont lus grâce à `--env-file`).

## Tests et qualité

```bash
vendor/bin/phpunit
vendor/bin/phpstan analyse
vendor/bin/php-cs-fixer fix --dry-run --diff
```

La même suite tourne dans GitHub Actions sur chaque push et chaque pull request vers `master`.

## Déploiement (Railway)

- Pre-deploy command : `php bin/console doctrine:migrations:migrate --no-interaction --allow-no-migration`
- **Ne jamais lancer les fixtures en production** (elles vident la base).

---

## Auteur

**Damien Chauveau**, développeur fullstack PHP / Symfony & Angular, Bordeaux.
Près de dix ans dans l'assurance avant de passer au développement.

[Portfolio](https://www.damienchauveau-dev.fr) · [LinkedIn](https://www.linkedin.com/in/chauveau-damien/) · [GitHub](https://github.com/DamienCH33)
