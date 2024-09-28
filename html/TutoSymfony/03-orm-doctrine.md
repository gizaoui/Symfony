# L'ORM Doctrine

## Environnement


Configurer la connexion à la base de données PostgreSQL via le fichier *TutoSymfony/.env* en indiquant la version (`psql -V`).

```bash
DATABASE_URL="postgresql://$POSTGRES_USER:$POSTGRES_PASSWORD@$POSTGRES_URL:5432/$POSTGRES_DB?serverVersion=15.8&charset=utf8"
```

L'url de connexion est construite via les variables d'environnements consignées dans le fichier *.bashrc*

```bash
# PGPASSWORD=postgres psql -h localhost -U postgres -d mydb
export POSTGRES_USER="postgres"
export POSTGRES_PASSWORD="postgres"
export POSTGRES_DB="mydb"
export POSTGRES_URL="localhost"
```

<br>

## Model

Création du *model* &nbsp;&#8640;&nbsp; `php bin/console make:entity Recipe`

###  Création des champs

Le *model* et le *reposirory*  de *Recipe* définis sous :

- **TutoSymfony/src/Entity/Recipe.php**
- **TutoSymfony/src/Repository/RecipeRepository.php**


#### **title**

```bash
 New property name (press <return> to stop adding fields):
 > title

 Field type (enter ? to see all types) [string]:
 > 

 Field length [255]:
 > 100 

 Can this field be null in the database (nullable) (yes/no) [no]:
 > 
```

#### **slug**

```bash
 New property name (press <return> to stop adding fields):
 > slug

 Field type (enter ? to see all types) [string]:
 > 

 Field length [255]:
 > 100

 Can this field be null in the database (nullable) (yes/no) [no]:
 >
```

#### **content**

```bash
 New property name (press <return> to stop adding fields):
 > content

 Field type (enter ? to see all types) [string]:
 > text

 Can this field be null in the database (nullable) (yes/no) [no]:
 >
```

#### **createdAt**

```bash
 New property name (press <return> to stop adding fields):
 > createdAt

 Field type (enter ? to see all types) [datetime_immutable]:
 > 

 Can this field be null in the database (nullable) (yes/no) [no]:
 >
```

#### **updatedAt**

```bash
 New property name (press <return> to stop adding fields):
 > updatedAt

 Field type (enter ? to see all types) [datetime_immutable]:
 > 

 Can this field be null in the database (nullable) (yes/no) [no]:
 > 
```
#### **duration**

```bash
 New property name (press <return> to stop adding fields):
 > duration

 Field type (enter ? to see all types) [string]:
 > integer

 Can this field be null in the database (nullable) (yes/no) [no]:
 > yes
```

<br>

## Création des tables

#### Script de création

- Fichiers de création des tables &nbsp;&#8640;&nbsp; `php bin/console make:migration`

Le script *TutoSymfony/migrations/**VersionYYYYMMDDXXXXXX.php*** doit être vérifié avant sont exécution.

#### Création sous *Postgres*

- Création des tables sous *ProsgreSQL* &nbsp;&#8640;&nbsp; `php bin/console doctrine:migration:migrate --no-interaction`


La table a été créée avec les types de champ suivants :

![03](pic/03.png)


## Données de test

```sql
TRUNCATE TABLE Recipe;

INSERT INTO "recipe" ("id", "title", "slug", "content", "created_at", "updated_at", "duration")
VALUES (1, 'Pate bonognaise', 'pate-bonognaise', 'Étape 1
Épluchez et émincez finement les oignons et l''ail.

Étape 2
Faites chauffer l''huile d''olive dans une poêle sur feu vif. Quand l’huile d’olive est bien chaude, déposez les oignons et l’ail émincés dans la poêle et faites-les revenir pendant 3 min, en remuant bien, jusqu''à ce que les oignons soient bien translucides. Ajoutez ensuite la viande de bœuf hachée, puis poursuivez la cuisson pendant 3 à 4 min sans cesser de mélanger, jusqu''à ce qu''elle ne soit plus rosée.

Étape 3
Incorporez les tomates pelées, les branches de thym et la feuille de laurier. Salez et poivrez selon vos goûts, ajoutez le sucre, puis mélangez. Baissez sur feu doux et laissez mijoter pendant 10 min environ, en remuant régulièrement.

Étape 4
Pendant ce temps, portez à ébullition un grand volume d’eau salée dans une casserole sur feu vif. Dès l’ébullition, plongez les spaghettis dans l’eau bouillante et laissez-les cuire en suivant les instructions du paquet ou jusqu’à ce qu’ils soient al dente. Lorsque les spaghettis sont cuits, égouttez-les dans une passoire et réservez-les au chaud.', '2024-09-28', '2024-09-28', '20');
```

## Récupération des données

Mondifions le fichiers *RecipeController.php*

```php
class RecipeController extends AbstractController
{
    #[Route('/recipe', name: 'recipe.index')]
    public function index(RecipeRepository $recipeRepository): Response {

      // Récupération des toutes les données de la table 
      $recipes = $recipeRepository->findAll();
      // Affichage des traces
      dd($recipes);
      ...
    }
    ...
}
```

Les traces du *controller* affiche le résultat suivant :

![04](pic/04.png)