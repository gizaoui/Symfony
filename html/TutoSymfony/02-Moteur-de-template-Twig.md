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

La page html des *recipes* contient inclus la page *base.html.twig* permattant l'intégration de le feuilles de style *app.css*.

```html
<!DOCTYPE html>
<html>
	<head>
		<meta charset="UTF-8">
		<title>
			{% block title %}Welcome!
			{% endblock %}
		</title>

		{% block stylesheets %}
			<!-- TutoSymfony/assets/styles/app.css -->
		{% endblock %}

		{% block javascripts %}
			{% block importmap %}
				{{ importmap('app') }}
			{% endblock %}
		{% endblock %}

	</head>
	<body>
		{% block body %}{% endblock %}
	</body>
</html>
```

