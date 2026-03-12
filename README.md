# Portfolio Développeur Symfony

Portfolio personnel développé avec **Symfony** permettant de présenter mes projets, mes compétences et de me contacter.
Il inclut également un **dashboard administrateur** permettant de suivre les visites du site et les messages envoyés via le formulaire de contact.

## Fonctionnalités

* Présentation des projets
* Affichage des compétences techniques
* Formulaire de contact
* Dashboard administrateur
* Statistiques de visites
* Graphique d'activité (Chart.js)

## Technologies utilisées

* PHP
* Symfony
* Twig
* Bootstrap
* Doctrine ORM
* PostgreSQL
* Docker
* Chart.js

## Installation

Cloner le projet :

```bash
git clone https://github.com/DamienCH33/portfolio2025.git
cd portfolio2025
```

Installer les dépendances :

```bash
composer install
```

Configurer l'environnement :

```
cp .env .env.local
```

Lancer les containers Docker :

```bash
docker compose up -d
```

Créer la base de données :

```bash
php bin/console doctrine:database:create
php bin/console doctrine:migrations:migrate
```

Charger les fixtures :

```bash
php bin/console doctrine:fixtures:load
```

## Auteur

Damien Chauveau
Développeur PHP / Symfony
