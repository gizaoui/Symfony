# PHP

- Lancement des serveurs : `drm && cd ~/git/github/Symfony/html && docker-compose up`
- Build du *Dockerfile* : `docker build -t mynginx .`
- Installation & lancement [port 8000](localhost:8000) : `docker run --name mynginx --rm -it -p 8000:80 mynginx:latest`
- Connexion au conteneur *nginx* (`cd /usr/share/nginx/html`) : `docker exec -it mynginx /bin/bash`
- Connexion au conteneur *php_fpm* (`cd /usr/share/nginx/html`) : `docker exec -it myphp_fpm /bin/bash`
- Test des fichiers de configuration : `nginx -t`
- Mettre à jour les droits (en test) : `sudo chmod -R 777 simple-app && chown -R gizaoui: simple-app/`

## Symfony

### Création & configuration du projet :
- `composer create-project symfony/skeleton:"7.1.*" simple-project`
- `cd simple-project && composer require webapp`
- Màj fichier [config/packages/framework.yaml](https://github.com/gizaoui/Symfony/blob/main/html/simple-project/config/packages/framework.yaml) (`http_method_override: true`)
	- `cd /usr/share/nginx/html/simple-project/config/packages && rm -f framework.yaml && wget https://raw.githubusercontent.com/gizaoui/Symfony/main/html/simple-project/config/packages/framework.yaml`
- Màj fichier [config/packages/twig.yaml](https://github.com/gizaoui/Symfony/blob/main/html/simple-project/config/packages/twig.yaml) (`form_themes: ['bootstrap_5_layout.html.twig']`)
	- `cd /usr/share/nginx/html/simple-project/config/packages && rm -f twig.yaml && wget https://raw.githubusercontent.com/gizaoui/Symfony/main/html/simple-project/config/packages/twig.yaml`

### Doctrine

- Supprimer le package empêchant la mise à jour de la base -> `composer remove symfony/ux-turbo`
- Màj fichier [.env](https://github.com/gizaoui/Symfony/blob/main/html/simple-project/.env) (`DATABASE_URL="postgresql://postgres:postgres@mypostgres:5432/mydb?serverVersion=15&charset=utf8"`)
	- `cd /usr/share/nginx/html/simple-project && rm -f .env && wget https://raw.githubusercontent.com/gizaoui/Symfony/main/html/simple-project/.env`

#### Model

Création de l'entité (*Recipe*) & du *repository* (RecipeRepository) -> `php bin/console make:entity Recipe`

```bash
# Repository
cd /usr/share/nginx/html/simple-project/src/Repository && rm -f RecipeRepository.php && \
wget https://raw.githubusercontent.com/gizaoui/Symfony/main/html/simple-project/src/Repository/RecipeRepository.php && \

# Entity
cd /usr/share/nginx/html/simple-project/src/Entity && rm -f Recipe.php && \
wget https://raw.githubusercontent.com/gizaoui/Symfony/main/html/simple-project/src/Entity/Recipe.php

# création du fichier 'migrations/Version[Date][Id].php'
php bin/console make:migration
```


### Formulaire

- Création d'un formulaire basé sur l'entité *Recipe* -> `php bin/console make:form RecipeType # (saisir l'entité 'Recipe')`

```bash
cd /usr/share/nginx/html/simple-project/src/Form && rm -f RecipeType.php && \
wget https://raw.githubusercontent.com/gizaoui/Symfony/main/html/simple-project/src/Form/RecipeType.php
```

### Controller

```bash
cd /usr/share/nginx/html/simple-project/templates && mkdir partials && cd partials && \
wget https://raw.githubusercontent.com/gizaoui/Symfony/main/html/simple-project/templates/partials/flash.html.twig && \
cd /usr/share/nginx/html/simple-project/templates && rm -f base.html.twig && \
wget https://raw.githubusercontent.com/gizaoui/Symfony/main/html/simple-project/templates/base.html.twig`
```

#### Controller *Home*

Création du controlleur -> `php bin/console make:controller HomeController`

```bash
# Controller
cd /usr/share/nginx/html/simple-project/config && rm -f routes.yaml && \
wget https://raw.githubusercontent.com/gizaoui/Symfony/main/html/simple-project/config/routes.yaml && \
cd /usr/share/nginx/html/simple-project/src/Controller && rm -f HomeController.php && \
wget https://raw.githubusercontent.com/gizaoui/Symfony/main/html/simple-project/src/Controller/HomeController.php

# Template
cd /usr/share/nginx/html/simple-project/templates/home && rm -f *.html.* && \
wget https://raw.githubusercontent.com/gizaoui/Symfony/main/html/simple-project/templates/home/index.html.twig
```

#### Controller *Recipe*

Création du controlleur -> `php bin/console make:controller RecipeController`

```bash
# Controller
cd /usr/share/nginx/html/simple-project/src/Controller && rm -f RecipeController.php && \
wget https://raw.githubusercontent.com/gizaoui/Symfony/main/html/simple-project/src/Controller/RecipeController.php

# Template
cd /usr/share/nginx/html/simple-project/templates/recipe && rm -f *.html.* && \
wget https://github.com/gizaoui/Symfony/blob/main/html/simple-project/templates/recipe/index.html.twig && \
wget https://raw.githubusercontent.com/gizaoui/Symfony/main/html/simple-project/templates/recipe/create.html.twig && \
wget https://raw.githubusercontent.com/gizaoui/Symfony/main/html/simple-project/templates/recipe/edit.html.twig && \
wget https://raw.githubusercontent.com/gizaoui/Symfony/main/html/simple-project/templates/recipe/show.html.twig
```

<br><hr><br>

## PhpPgAdmin

Mettre à jour le fichier *phppgadmin/conf/config.inc.php* :

```php
	$conf['servers'][0]['host'] = 'mypostgres';
	$conf['servers'][0]['port'] = 5432;
	$conf['extra_login_security'] = false;
```

---

Liens :

- [phppgadmin](http://localhost:8000/phppgadmin/)
- [Singleton](http://localhost:8000/Pattern/Singleton/index.php)
- [Blog](http://localhost:8000/Blog/public/index.php)
- [timestamp](https://www.commandprompt.com/education/how-to-insert-a-timestamp-into-a-postgresql-table/)
- [symfony](https://dev.to/eelcoverbrugge/starting-a-new-symfony-project-on-linux-2amh)
- [doctrine : 'composer require orm'](https://zestedesavoir.com/tutoriels/1713/doctrine-2-a-lassaut-de-lorm-phare-de-php/les-bases-de-doctrine-2/sauvegarder-des-entites-grace-a-doctrine/)

