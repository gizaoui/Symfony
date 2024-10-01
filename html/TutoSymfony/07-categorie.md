# Catégorie

## Reconfiguration du projet 

On souhaite réorganiser le projet en déplaçant la gestion des recettes au niveau d'un *back-office*.

- Re-création d'un *controller Recipe*  &nbsp;&#8640;&nbsp; `php bin/console make:controller admin\\RecipeController`


Mettre à jour les *path* du fichier *TutoSymfony/src/Controller/Admin/**RecipeController.php***.

- Route &nbsp;&#8640;&nbsp; `#[Route('/admin/recipe', name: 'admin.recipe.index')]`
- Rendu &nbsp;&#8640;&nbsp; `return $this->render('admin/recipe/index.html.twig' ...`
- Redirection &nbsp;&#8640;&nbsp; `return $this->redirectToRoute('admin.recipe.index');`

<br>

Dupliquer le fichier *base.html.twig* sous *TutoSymfony/templates/admin/**base.html.twig*** et mettre à jour la *navbar*.

```html
...
<nav class="navbar navbar-light mb-2 navbar-expand-sm" style="background-color: #f7e3fd;">
	<div class="container-fluid">
	   <button class="navbar-toggler collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#monsite" aria-controls="monsite" aria-expanded="false" aria-label="Toggle navigation">
	      <span class="navbar-toggler-icon"></span>
	   </button>
		<div class="collapse navbar-collapse" id="monsite">
			<ul class="navbar-nav me-auto">
				<li class="nav-item">
					<a class="nav-link {{ app.current_route=='home'?'active':'' }}" href="{{ path("home") }}">Acceuil</a>
				</li>
				<li class="nav-item">
					<a class="nav-link {{ app.current_route starts with 'admin.recipe.'?'active':'' }}" href="{{ path("admin.recipe.index") }}">Recipe</a>
				</li>
			</ul>
		</div>
	</div>
</nav>
...
```

<br>

Ajouter le lien pointant vers l'interface d'administration dans le fichier *TutoSymfony/templates/**base.html.twig*** et supprimer celui pointant vers la liste des recettes.

```html
...
<li class="nav-item">
	<a class="nav-link {{ app.current_route starts with 'admin.recipe.'?'active':'' }}" href="{{ path("admin.recipe.index") }}">Admin</a>
</li>
...
```

<br>

Mettre à jour les liens et l'*extends* du fichier *TutoSymfony/templates/admin/recipe/i**ndex.html.twig***.

```html
<!-- Nouvelle navbar -->
{% extends 'admin/base.html.twig' %}

{% block title %}Hello RecipeController!
{% endblock %}
{% block body %}

	<h1 class="mb-3">Recipes</h1>

	<p class="mb-1">
		<a class="btn btn-primary btn-sm" href="{{ path("admin.recipe.create") }}">Nouvelle recette</a>
	</p>

	<table class="table">
		<tbody>
			<tr>
				<th>Titre</th>
				<th>Action</th>
			</tr>
		</tbody>
		{% for id, recipe in recipes %}
			<tr>
				<td>
					<a href="{{ url('admin.recipe.show', { id: recipe.id }) }}">{{ recipe.title }}</a>
				</td>
				<td>
					<div class="d-flex gap-1">
						<a class="btn btn-primary btn-sm" href="{{ path("admin.recipe.edit", {id: recipe.id }) }}">Editer</a>
						<form
							action="{{ path("admin.recipe.delete", {id: recipe.id }) }}" method="post">
							<input type="hidden" name="_method" value="DELETE">
							<button type="submit" class="btn btn-danger btn-sm">Supprimer</button>
						</form>
					</div>
				</td>
			</tr>
		{% endfor %}
	</table>
{% endblock %}
```

<br>

Mettre à jour les *extends* des fichiers (`{% extends 'admin/base.html.twig' %}`) :

- *TutoSymfony/templates/admin/recipe/**create.html.twig***
- *TutoSymfony/templates/admin/recipe/**edit.html.twig***
- *TutoSymfony/templates/admin/recipe/**show.html.twig***

<br>

- :warning: Supprimer le répertoire *TutoSymfony/templates/**recipe*** après avoir vérifié les redirections.
- :warning: Supprimer le fichier *TutoSymfony/src/Controller/**RecipeController.php*** après avoir vérifié les redirections.


