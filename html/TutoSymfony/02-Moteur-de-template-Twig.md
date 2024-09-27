# Moteur de template Twig


Le *render* de l'url permet de renvoyer une [page](http://localhost:8000/recipe) html avec des données envoyées par le paramètre ***controller_name***.

```php
class RecipeController extends AbstractController {

    #[Route('/recipe', name: 'recipe.index')]
    public function index(): Response {
        return $this->render('recipe/index.html.twig', [
            'controller_name' => 'Recipe Controller',
        ]);
    }
}
```

Le paramètre ***controller_name*** est affiché dans sa balise. 

```html
{% extends 'base.html.twig' %}

{% block title %}Hello RecipeController!{% endblock %}

{% block body %}
<style>
    .example-wrapper { margin: 1em auto; max-width: 800px; width: 95%; font: 18px/1.5 sans-serif; }
    .example-wrapper code { background: #F5F5F5; padding: 2px 6px; }
</style>

<div class="example-wrapper">
    <h1>Hello "{{ controller_name }}" ✅</h1>
</div>
{% endblock %}
```

La page html des *recipes* contient inclus la page *base.html.twig* permattant l'intégration de le feuilles de style *app.css*.<br>
On y intégre *bootstrap* de la façon suivante.

```html
<!DOCTYPE html>
<html>
	<head>
		<meta charset="UTF-8">
		<title>
			{% block title %}Welcome!
			{% endblock %}
		</title>

		<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
		{% block stylesheets %}
			<!-- TutoSymfony/assets/styles/app.css -->
		{% endblock %}

		<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
		{% block javascripts %}
			{% block importmap %}
				{{ importmap('app') }}
			{% endblock %}
		{% endblock %}

	</head>
	<body>
		<nav class="navbar navbar-light mb-2 navbar-expand-sm" style="background-color: #f7e3fd;">
			<!-- La navbar est commune à l'ensemble des fichiers (cf. app.css pour la customisation) -->
		</nav>

		<div class="container my-4">
			<!-- Fichiers incluant des 'base.html.twig' -->
			{% block body %}{% endblock %}
		</div>
	</body>
</html>
```

<br>

Il est possible d'envoyer une structure de données sur la [page](localhost:8000/recipe/pate-bolognaise/32) html. 

```php
    #[Route('/recipe/{slug}/{id}', name: 'recipe.show')]
    public function show(Request $request, string $slug, int $id): Response {
        return $this->render('recipe/show.html.twig', [
            'slug' => $slug,
            'id' => $id,
            'person' => [
                'firstname' => 'John',
                'lastname' => 'DOE'
            ]
        ]);
    }
```

Elle sera recupéré de la façon suivante :

```html
	<div class="example-wrapper">
		<h1>Show recipes</h1>
		<ul>
			<li><strong>slug :</strong> {{ slug }}</li>
			<li><strong>id :</strong> {{ id }}</li>
            <li><strong>firstname :</strong> {{ person.firstname }}</li>
            <li><strong>lastname :</strong> {{ person.lastname }}</li>
            <li><strong>login :</strong> {{ person.firstname ~ person.lastname | lower }}</li>
		</ul>
	</div>
```
