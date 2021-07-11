# Symfony France

## Objectifs 
1. Donner un maximum de visibilité à une grande variété de métiers faisant partie intégrante du patrimoine culturel français.
Les agriculteurs, artisants, artistes, indépendants, commerçants, maraichers, bouchers, boulangers, y sont représentés.

2. Les visiteurs pourront trouver des indépendants proches de chez eux, et les informations pratiques    

3. Mutualiser au sein d'un même site différents corps de métiers.

Chaque indépendant dispose d'une page qui lui est propre, qu'il peut administrer à sa guise, et publier des actualités.

### Cibles 
Le site web s'adresse à des utilisateurs de n'importe quel âge

## Utilisateurs et sécurité

Les rôles utilisateurs sont les suivants :

* `ROLE_ADMIN` : Administrateur qui a tous les droits
* `ROLE_JOB` : Entrepreneur qui a le droit de  
  * Créer des articles
  * Modifier sa page de présentation
  * Modifier ses informations de contact
    * Téléphone
    * Adresse
    * Email
    * Site web
  * Modifier sa géolocalisation
  * Modifier les informations pratiques  

## Fonctionnalités

### Entités

#### Job 
Entité qui représente un entrepreneur

#### JobCategory
La catégorie d'un `Job`

Le megamenu du site se construit à partir de toutes les catégories

#### JobArticle
Chaque entrepreneur peut créer lui même ses propres articles

### Gestion de contenu

#### Content
