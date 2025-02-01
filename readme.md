# Projet Symfony - Boîte à Annonces

## Auteur
Nicolas Montard

## Prérequis

Avant de démarrer le projet, assurez-vous que vous avez installé les outils suivants sur votre machine :

- **PHP** >= 8.1
- **Composer** >= 2.x
- **Node.js** >= 16.x
- **Yarn** (ou npm) pour la gestion des dépendances front-end
- **MySQL** ou **MariaDB** pour la base de données
- **Symfony CLI**

## Installation
2. Installer les dépendances

Installez les dépendances PHP avec Composer :

### 1. Cloner le projet

Clonez le projet depuis le dépôt Git :

git clone https://github.com/votre-utilisateur/boite-annonces.git

### 2. Installer les dépendances

Installez les dépendances :

composer install

yarn install

### 3. Configurer l'environnement

Copiez le fichier .env pour créer un fichier .env.local et personnalisez les paramètres d'environnement :

DB_URL : Configurez votre connexion à la base de données. Exemple :

DATABASE_URL="mysql://username:password@localhost:3306/boite_annonces"

### 4. Créer la base de données

Exécutez les commandes suivantes pour créer la base de données et les tables :

php bin/console doctrine:database:create

php bin/console doctrine:migration:migrate

### 5. Génération des clés JWT

Pour générer les clés :

php bin/console lexik:jwt:generate-keypair

### 6. Lancement du serveur web

symfony serve

## Fonctionnalités

### API

## Login

La route pour se connecter en API et obtenir son token est :

http://127.0.0.1:8000/api/login

Il faut lui envoyer dans le body de la requête en json:

{
    "email": "example@email",
    "password": "password"
}

Un token JWT est par la suite reçu.

## Annonce

Pour accéder aux annonces en API, il faut utiliser les routes :

GET http://127.0.0.1:8000/api/annonce/

GET http://127.0.0.1:8000/api/annonce/{id}

POST http://127.0.0.1:8000/api/annonce/

Il faut passer à ces routes le token JWT obtenu précédemment avec :

La clé : Authorization

La valeur : Bearer <token JWT>

### Note

De nombreuses fonctionnalités n'ont pas pu être incorporées par manque de temps