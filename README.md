# WildWonderHub (SAE 3.01)

WildWonderHub est une application de gestion des visiteurs et des animaux du Zoo de la Palmyre, utilisant principalement le framework Symfony (version 6.3).

## Table des matières

<!-- TOC -->
  * [WildWonderHub (SAE 3.01)](#WildWonderHub--sae-301-)
  * [Les auteurs du projet](#auteurs-du-projet)
  * [Outils utilisés](#outils-utilisés)
  * [Mise en place](#mises-en-place)
  * [Authorisations et utilisateurs de démonstration](#authorisations-et-utilisateurs-de-dmonstration)
  * [Rappel pour le projet](#rappel-pour-le-projet)
<!-- TOC -->

## Auteurs du projet

- Logan Jacotin
- Romain Leroy
- Vincent Kpatinde
- Clément Perrot

## Outils utilisés

- Symfony
- PhpCsFixer
- Codeception
- EasyAdmin
- Orm-fixtures
- Zenstruck/foundry
- EasyAdmin2
- IconFinder (pour les icônes d'utilisateur, toutes gratuites)
- Plugins Tabler Icons de Figma (pour les icônes de l'application, toutes gratuites)
- Pexels et Unsplash, pour les images et vidéos de l'application, toutes gratuites et libres de droit

## Mises en place

- Installation du projet
```shell
git clone https://iut-info.univ-reims.fr/gitlab/jaco0024/sae3-01-zoo.git
````

Après, se placer dans le repertoire du projet :
```shell
cd sae3-01-zoo
```
Pour installer toutes les dépendances du projets
```shell
composer install 
```

**Ensuite, reconfigurer l'accès à la base de données en redéfinissant le fichier ".env.local"**

Tester si votre projet est conforme à a norme PSR-12 :
```shell
composer test:cs
```

Rend le  projet conforme à la norme PSR-12 :
```shell
composer fix:cs
```

Permet de tester le code, de détruire la bd, de la recréer et enfin de créer le schéma :

```shell
composer test:codeception
```

Permet de mettre en forme le projet et de tester le projet :

```shell
composer test
```


Lancer le serveur Web :
```shell
composer start
```
**Lancez votre navigateur web et saisissez l'URL suivante :**

http://127.0.0.1:8000
**ou**
http://localhost:8000

Connexion à la bd (.env.local):

```shell
DATABASE_URL="mysql://user:password@mysql:3306/dbName?serverVersion=10.11.2-MariaDB&charset=utf8mb4"`
```
Générer la base de données et les données factices :

```shell
composer db
```
Cette commande détruit la bd si elle existe, en crée une nouvelle, effectue les migrations et génère les données factices.

## Autorisations et utilisateurs de démonstration


| Email               | Mot de passe | Type de compte |
|---------------------|--------------|----------------|
| root@example.com    | test         | Administrateur |
| user@example.com    | test         | Utilisateur    |
| employe@example.com | test         | Employé du zoo |

## Rappel pour le projet

- Créer une branche contenant le nom de la fonctionnalité traitée


```shell
git branch <nom>
```

- Se positionner sur cette branche


```shell
git checkout <nom>
```

- Retourner sur la branche main une fois le code écrit

```shell
git checkout main
git pull
```

- Retourner sur sa branche locale
```shell
git checkout <nom>
git rebase main
```

- Push le code écrit
- Demande de merge request sur le repo gitlab

**Exemple de commit**

* ajout d’une fonctionnalité

```shell
git commit -m "add: <fonctionnalité ajoutée>"
```

* modification d’une fonctionnalité déjà présente

```shell
git commit -m "edit: <fonctionnalité modifiée>"
```
* suppression d’un fichier

```shell
git commit -m "delete: <fonctionnalité supprimée>"
```

```shell
git commit -m "edit(<component>): <fonctionnalité modifiée sur le dit component>"
```

## Entity
**Arboresence**
- Animal
* private string NameAnm = null
* private int Gender = null
* private string DescAnm = null
* private float Weight = null
* private float Size = null<
* private DateTimeInterface $BirthDate = null

- Employee
* private int id = null
* private int idSupEmp = null

- Event
* private int id = null
* private string Namevent = null
* private DateTimeInterface DateEvent = null
* private DateTimeInterface Hstartevent = null
* private DateTimeInterface Hendevent = null
* private int MaxiNumPlace = null
* private string DesEvent = null

- Family</br>
* private int id = null</br>
* private string NameFam = null
* private string DescFam = null

- Pen</br>
* private int id = null
* private int Capacity = null
* private string TypePen = null
* private float SizePen = null
* private int Numplace = null

- Species</br>
*  private int id = null<
* private string NameSpe = null
* private string Diet = null
* private string Origin = null
* private string Descspe = null

- Spot
* private int id = null
* private int CodeSpot = null
* private string TypeSpot = null
* private string NameSpot = null

- Ticket</br>
* private int id = null
* private DateTimeInterface Date = null
* private int Price = null
* private int Period = null

- Visitor