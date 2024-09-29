# Les formulaires

Commençons par créer le formulaire contruit selon l'objet *Recipe*.<br>
:warning: Les données associées au formulaire seront gérées dans le *controller*.

Création du formulaire &nbsp;&#8640;&nbsp; `php bin/console make:form RecipeType`

```bash
 The name of Entity or fully qualified model class name that the new form will be bound to (empty for none):
 > Recipe
```

<br>

Le formulaire généré est la suivant :

```php
class RecipeType extends AbstractType {

    public function buildForm(FormBuilderInterface $builder, array $options): void {
        $builder
            ->add('title')
            ->add('slug')
            ->add('content')
            ->add('createdAt', null, ['widget' => 'single_text', ])
            ->add('updatedAt', null, ['widget' => 'single_text', ])
            ->add('duration');
    }

    public function configureOptions(OptionsResolver $resolver): void {
        $resolver->setDefaults([
            // Formulaire va traiter un objet de type 'Recipe'
            'data_class' => Recipe::class,
        ]);
    }
}
```

<br>

L'intégration du formulaire dans la nouvelle page web *TutoSymfony/templates/recipe/**edit.html.twig*** est obtenu via le *controller*.
:warning: Pour le moment, il ne permet pas d'effectuer de mise à jour.

```php
#[Route('/recipe/edit/{id}', name: 'recipe.edit')]
// Récupération par la 'Primary key' dans l'instance '$recipe'
public function edit(Recipe $recipe): Response {

// Création de l'instance du formulaire initialisée 
// avec l'injection des données dans l'instance '$recipe'.
$form = $this->createForm(RecipeType::class, $recipe);

return $this->render('recipe/edit.html.twig', [
   'recipeData' => $recipe, // Données récupérées de la base
   'recipeForm' => $form // Formulaire
  ]);
}
```

<br>

Le formulaire est affiché sur la web *TutoSymfony/templates/recipe/**edit.html.twig*** via la balise *form*.<br>


```html
<h1>{{ recipeData.title }}</h1> <!-- Données récupérées de la base -->
{{ form(recipeForm)}} <!-- Formulaire -->
```
<br>

On obtient le rendu disgracieux suivant :

![09](pic/09.png)

<br>

Cela peut-être corrigé par la mise à jour du fichier *TutoSymfony/config/packages/**twig.yaml***

```yaml
twig:
    file_name_pattern: '*.twig'
    form_themes: ['bootstrap_5_layout.html.twig']
```

<br>

Le rendu est à présent le suivant :

![11](pic/11.png)

<br>

La mise en page peut encore être améliorée en distinguant l'ensemble des champs.

```html
<h1>{{ recipeData.title }}</h1>
{{ form_start(recipeForm) }}
<div class="d-flex gap-2">
	{{ form_row(recipeForm.title) }}
	{{ form_row(recipeForm.slug) }}
</div>
<div class="d-flex flex-column">
	{{ form_row(recipeForm.content) }}
   {{ form_row(recipeForm.duration) }}
</div>
<div class="d-flex gap-2">
	{{ form_row(recipeForm.createdAt) }}
	{{ form_row(recipeForm.updatedAt) }}
</div>
{{ form_end(recipeForm) }}
```

<br>

Ce permet le rendu suivant :

![12](pic/12.png)



L'édition d'une recette peut être facilité en modifiant la page web *TutoSymfony/templates/recipe/**index.html.twig***.

```html	<h1>Recipes</h1>
<table class="table">
	<tbody>
		<tr>
			<th>Titre</th>
			<th>Action</th>
		</tr>
	</tbody>
	{% for id, recipe in recipes %}
		<tr>
			<td><a href="{{ url('recipe.show', { id: recipe.id }) }}">{{ recipe.title }}</a></td>
			<td><a class="btn btn-primary btn-sm" href="{{ path("recipe.edit", {id: recipe.id }) }}">Editer</a></td>
		</tr>
	{% endfor %}
</table>
```

<br>

Il suffira de cliquer sur le bouton *Editer*

![10](pic/10.png)

