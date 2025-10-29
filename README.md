# MongoDB Training

Projet d'entraînement pour pratiquer MongoDB avec PHP et Composer.

## Pré-requis

Avant de commencer, assurez-vous d'avoir installé :

- PHP
- Composer
- MongoDB Community
- MongoDB Shell (`mongosh`)

## Setup

Pour configurer le projet, exécutez les commandes suivantes :

```bash
# Installer les dépendances PHP
composer install

# Créer la base de données et les collections
php migrations/create_bibliotheque.php

# Lancer le shell MongoDB
mongosh
```

## Défis

0. Voir toutes les collections
1. Trouver tous les livres disponibles
2. Trouver tous les livres d’un genre donné (ex: "Science-fiction")
3. Trouver les livres écrits après l’an 2000
4. Mettre à jour la disponibilité d’un livre
5. Trouver tous les livres sans auteur
6. Supprimer tous les livres sans auteur
7. Trier les 5 livres les plus récents
8. Afficher uniquement le titre et l’année
10. Trouver les livres contenant un mot particulier dans un titre
11. Compter le nombre de livres par genre
12. Compter combien de lecteurs sont premium vs standard
13. Trouver le nombre total d’emprunts
14. Lister tous les livres avec le nom de leur auteur
18. Nombre moyen d’emprunts par lecteur
19. Nombre d’emprunts par mois (chronologique)
20. Analyse multifacette : répartition par genre et par type d’abonnement
21. Créer un index pour accélérer la recherche par genre
22. Afficher l'index créé