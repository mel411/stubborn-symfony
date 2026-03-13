# Stubborn – Site e-commerce Symfony


Ce projet a été réalisé dans le cadre de ma formation de Développeur Web Full-Stack.


L'objectif était de créer un site e-commerce fonctionnel avec Symfony, comprenant un catalogue de produits, un panier, une authentification utilisateur et un espace d'administration pour gérer les produits.


## Technologies utilisées

- PHP

- Symfony

- Twig

- Doctrine ORM

- MySQL

- PHPUnit


## Fonctionnalités principales

Le site permet de :


- consulter un catalogue de produits

- voir la fiche détaillée d’un produit

- ajouter des produits au panier

- consulter son panier

- finaliser une commande (simulation de paiement)


Le projet comprend également :


- un système d’authentification utilisateur

- une vérification d’email

- un back-office administrateur pour gérer les produits

- la gestion du stock par taille (XS, S, M, L, XL)

- des tests automatisés avec PHPUnit


## Back-office

Un espace administrateur permet de :

- ajouter un produit

- modifier un produit

- supprimer un produit

- gérer les stocks par taille


## Base de données

La base de données contient principalement deux entités :

- **Product** : informations du produit (nom, prix, image…)

- **Stock** : gestion des quantités par taille


## Lancer le projet



1. Installer les dépendances :

composer install

2. Configurer la base de données dans le fichier `.env`

3. Créer la base de données :

php bin/console doctrine:database:create

4. Exécuter les migrations :

php bin/console 

doctrine:migrations:migrate

5. Lancer le serveur Symfony :

symfony server:start

Le site sera accessible sur :

http://localhost:8000


## Tests

Les tests peuvent être lancés avec la commande :
php vendor/bin/phpunit


## Auteur

Projet réalisé par **Melina Yorgova** dans le cadre de la formation CEF.
