# 🗺️ Noms de Villes

Application web cartographique permettant de visualiser sur une carte les caractéristiques des noms de communes en France.

## Fonctionnalités

- Recherche de communes par **début**, **fin** ou **contenu** du nom
- Affichage des résultats sur une carte interactive (Leaflet)
- Popup avec le nom de la commune au clic
- 4 recherches prédéfinies : K, Plou, heim, ac
- **Fonctionnalité différenciante : Barycentre**

## Fonctionnalité différenciante : Barycentre

Après chaque recherche, l'application calcule automatiquement le **barycentre géographique** (centre moyen) de toutes les communes trouvées.

Le barycentre est calculé en faisant la moyenne des latitudes et longitudes de toutes les communes retournées. Un bouton "Afficher le barycentre" place un marqueur spécial sur la carte à ce point central.

Cela permet de visualiser la **concentration géographique** d'un type de nom. Par exemple :
- Les communes en **"Plou"** → barycentre en Bretagne (origine celtique)
- Les communes en **"heim"** → barycentre en Alsace (origine germanique)
- Les communes en **"ac"** → barycentre dans le Sud-Ouest (origine gauloise)

## Stack technique

- **Backend** : PHP + Flight (micro-framework)
- **Base de données** : MySQL — table `communes` de `geobase`
- **Frontend** : Vue.js 3 + Leaflet
- **Fond de carte** : OpenStreetMap

## Installation

1. Cloner le dépôt dans `htdocs` de XAMPP
2. Installer les dépendances PHP :
```bash
composer install
```
3. Importer `geobase` dans MySQL
4. Lancer Apache via XAMPP
5. Accéder à `http://localhost/noms-de-villes/`

## API

`GET /api/villes?type={starts|ends|contains}&q={texte}`

Retourne un tableau JSON de communes :
```json
[
  { "nom": "Paris", "lon": 2.342, "lat": 48.856 }
]
```

## Auteur

Amine Chebil — M1 IGAST