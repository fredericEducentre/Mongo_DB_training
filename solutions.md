# Solutions MongoDB Training

1. Trouver tous les livres disponibles
```
db.livres.find({ disponible: true })
```

- db.livres → accède à la collection livres.
- find({ disponible: true }) → filtre les documents où le champ disponible est true.
- Résultat : liste de tous les livres disponibles.

2. Trouver tous les livres d’un genre donné
```
db.livres.find({ genre: "Science-fiction" })
```

- Filtre les livres dont le champ genre est exactement "Science-fiction".

3. Trouver les livres écrits après l’an 2000
```
db.livres.find({ annee: { $gt: 2000 } })
```

- $gt signifie "greater than" (supérieur à).
- On sélectionne les livres dont l’année est supérieure à 2000.

4. Mettre à jour la disponibilité d’un livre
```
db.livres.updateOne({ _id: 10 }, { $set: { disponible: false } })
```

- updateOne → met à jour un seul document correspondant au filtre { _id: 10 }.

- $set → définit le champ disponible à false.

5. Trouver tous les livres sans auteur
```
db.livres.find({ auteur_id: { $exists: false } })

ou

db.livres.find({ auteur_id: null })

```

- $exists: false → sélectionne les documents où le champ auteur_id n’existe pas.

6. Supprimer tous les livres sans auteur 
```
db.livres.deleteMany({ auteur_id: { $exists: false } })

ou

db.livres.deleteMany({ auteur_id: null })
```
- Supprime tous les documents qui n’ont pas de champ auteur_id.

7. Trier les 5 livres les plus récents
```
db.livres.find().sort({ annee: -1 }).limit(5)
```

- sort({ annee: -1 }) → trie par année décroissante (du plus récent au plus ancien).

- limit(5) → ne retourne que les 5 premiers résultats.

8. Afficher uniquement le titre et l’année des livres
```
db.livres.aggregate([
    {
        $project: {
            _id: 0,
            titre: 1,
            annee: 1
        }
    }
])

```
- { titre: 1, annee: 1, _id: 0 } → sélectionne uniquement les champs titre et annee, et exclut _id.

10. Trouver les livres contenant un mot particulier dans un titre
```
db.livres.find({ titre: { $regex: "Beatae" } })
```
- $regex permet de mettre des expressions régulière

11. Compter le nombre de livres par genre
```
db.livres.aggregate([
  { $group: { _id: "$genre", total: { $sum: 1 } } },
  { $sort: { total: -1 } }
])
```
- \$group → regroupe les livres par genre (\_id: "$genre").
- $sum: 1 → compte chaque document du groupe.
- $sort → trie par nombre total décroissant.

12. Compter combien de lecteurs sont premium vs standard
```
db.lecteurs.aggregate([
  { $group: { _id: "$abonnement", nb: { $sum: 1 } } }
])
```
- Même principe que l’exemple précédent mais pour les abonnements des lecteurs.

13. Trouver le nombre total d’emprunts
```
db.emprunts.countDocuments()
```
- countDocuments → retourne le nombre total de documents dans la collection emprunts

14. Lister tous les livres avec le nom de leur auteur
```
db.livres.aggregate([
  { 
    $lookup: { 
      from: "auteurs", 
      localField: "auteur_id", 
      foreignField: "_id", 
      as: "auteur" 
    }
  },
  { $unwind: "$auteur" },
  { $project: { titre: 1, "auteur.nom": 1, "auteur.nationalite": 1, _id: 0 } }
])
```
- $lookup → fait une jointure avec la collection auteurs.
- localField: "auteur_id" → champ du livre.
- foreignField: "_id" → champ correspondant dans auteurs.
- $unwind → transforme le tableau auteur en document unique.
- $project → sélectionne les champs à afficher.

18. Nombre moyen d’emprunts par lecteur
```
db.emprunts.aggregate([
  { $group: { _id: "$lecteur_id", nb_emprunts: { $sum: 1 } } },
  { $group: { _id: null, moyenne: { $avg: "$nb_emprunts" } } }
])
```

19. Nombre d’emprunts par mois (chronologique)
```
db.emprunts.aggregate([
  {
    $group: {
      _id: { mois: { $month: "$date_emprunt" }, annee: { $year: "$date_emprunt" } },
      total: { $sum: 1 }
    }
  },
  { $sort: { "_id.annee": 1, "_id.mois": 1 } }
])
```
- \$month et $year → extraient le mois et l’année de la date d’emprunt.
- $group → regroupe les emprunts par mois/année.
- $sort → trie chronologiquement.

20. Analyse multifacette : répartition par genre et par type d’abonnement
```
db.emprunts.aggregate([
  {
    $facet: {
      par_genre: [
        { $lookup: { from: "livres", localField: "livre_id", foreignField: "_id", as: "livre" } },
        { $unwind: "$livre" },
        { $group: { _id: "$livre.genre", total: { $sum: 1 } } }
      ],
      par_abonnement: [
        { $lookup: { from: "lecteurs", localField: "lecteur_id", foreignField: "_id", as: "lecteur" } },
        { $unwind: "$lecteur" },
        { $group: { _id: "$lecteur.abonnement", total: { $sum: 1 } } }
      ]
    }
  }
])
```

- $facet → permet de lancer plusieurs pipelines en parallèle.
- par_genre → calcule le nombre d’emprunts par genre.
- par_abonnement → calcule le nombre d’emprunts par type d’abonnement.

21. Créer un index pour accélérer la recherche par genre
```
db.livres.createIndex({ genre: 1 })
```

- Crée un index sur le champ genre.
- 1 → ordre croissant.
- Améliore les performances des requêtes filtrant par genre.

22. Voir tous les index d’une collection
```
db.livres.getIndexes()
```
- Affiche tous les index existants dans la collection livres.
- Utile pour vérifier si un index a bien été créé.