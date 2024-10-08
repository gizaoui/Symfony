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

## Affichage du formulaire

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

La mise en page peut encore être améliorée en distinguant l'ensemble des champs du fichier *TutoSymfony/templates/recipe/**edit.html.twig***.

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

<br>

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

<br>

## Mise à jour d'une recette

Mettre à jour le formulaire en y ajoutant un bouton ***Submit*** et le typage des données.<br>
Le paramètre `empty_data` permet l'initialisation des champs.
Les champs texte sont initialisés à vide au lieu de *null*. Non autorisé dans les *Entity*.


```php
use Symfony\Component\Form\Extension\Core\Type\TextType;
...

class RecipeType extends AbstractType {
   public function buildForm(FormBuilderInterface $builder, array $options): void {
     $builder
         ->add('title', TextType::class, ['label' => 'Titre', 'empty_data' => ''])
         ->add('slug', TextType::class, ['label' => 'Path', 'empty_data' => ''])
         ->add('content', TextareaType::class, ['label' => 'Contenu', 'empty_data' => ''])
         ->add('createdAt', DateTimeType::class )
         ->add('updatedAt', DateTimeType::class )
         ->add('duration', IntegerType::class)
         ->add('save', SubmitType::class, ['label' => 'Envoyer']);
   }
   ...
}
```
<br>

Le bouton a été ajouté et le titre modifié afin de tester la mise à jour

![13](pic/13.png)


<br>

Modifier le *controller* pour prendre en charge la mise à jour d'un enregistre.

```php
#[Route('/recipe/edit/{id}', name: 'recipe.edit')]
public function edit(Recipe $recipe, 
                     Request $request, 
                     EntityManagerInterface $em): Response  {

   // Création de l'instance du formulaire initialisée 
   // avec l'injection des données dans l'instance '$recipe'.
   $form = $this->createForm(RecipeType::class, $recipe);

   // Récupère les données mise à jour de la page web.
   // pour mettre à jour celle de l'instance '$recipe'
   // L'absence de données dans la $request au premier appel
   // n'écrase pas celles présentes dans l'instance '$recipe'.
   $form->handleRequest($request);

   // Information obtenues par le 'handleRequest'
   // précédement appelé.
   if($form->isSubmitted() && $form->isValid()) {
      // Mise à jour de la base de données.
      $em->flush();
      // Redirection vers la liste des recettes.
      return $this->redirectToRoute('recipe.index');
   }

   // Page permettant la mise à jour d'une recette
   return $this->render('recipe/edit.html.twig', [
      'recipeData' => $recipe,
      'recipeForm' => $form
     ]);
}
```

<br>

La mise à jour a correctement été effectuée.

![14](pic/14.png)

<br>

## Message *flash*

On souhaitre retourner l'information de le transaction de mise à jour dans une boîte de message.


```php
#[Route('/recipe/edit/{id}', name: 'recipe.edit')]
public function edit(Recipe $recipe, 
                     Request $request, 
                     EntityManagerInterface $em): Response  {
   ...
   if($form->isSubmitted() && $form->isValid()) {
      $em->flush();
      // Envoie du message dans variable globale 'app'
      $this->addFlash('success', 'La recette a bien été modifié');
      return $this->redirectToRoute('recipe.index');
   }
   ...
}
```

<br>


Le message est récupéré dans l'objet *app.flashes* dans le fichier html commun *TutoSymfony/templates/**base.html.twig*** suite à la mise à jour du titre.

```html
<div class="container my-4">
   <!-- Récupération du message 'Flash' -->
	{{ dump(app.flashes) }}
	<!-- Fichiers incluant des 'base.html.twig' -->
	{% block body %}{% endblock %}
</div>
```

<br>

Le message apparait à travers deux tableaux imbriqués.

![15](pic/15.png)

<br>

L'affichage des messages messages seront traités dans le fichier *TutoSymfony/templates/partials/**flash.html.twig*** de la façon suivante :

```html
{% for type, messages in app.flashes %}
<div class="alert alert-{{ type }}">
	{{ messages | join('. ') }}
</div>
{% endfor %}
```

<br>

Ce fichier sera inclus dans le fichier commun *TutoSymfony/templates/partials/**flash.html.twig***.

```html
<div class="container my-4">
   <!-- Récupération du message 'Flash' -->
	{% include 'partials/flash.html.twig' %}
	<!-- Fichiers incluant des 'base.html.twig' -->
	{% block body %}{% endblock %}
</div>
```

<br>

Ce qui permet d'obtenir le rendu suivant :

![16](pic/16.png)


<br>

## Création d'une nouvelle recette


Modifier le *controller* pour prendre en charge l'ajout d'une nouvel recette.

```php
#[Route('/recipe/create', name: 'recipe.create')]
// Récupération par la 'Primary key' dans l'instance '$recipe'
public function create( Request $request, EntityManagerInterface $em): Response {

   // Création d'un objet Recipe permetant 
   // l'intégration d'un nouvel enregistrement.
   $recipe = new Recipe();

   // Création de l'instance du formulaire initialisée 
   // avec l'injection des données dans l'instance '$recipe'.
   // Vide dans le cas présent.
   $form = $this->createForm(RecipeType::class, $recipe);

   // Récupère les données mise à jour de la page web.
   // pour mettre à jour celle de l'instance '$recipe'
   $form->handleRequest($request);

   // Information obtenues par le 'handleRequest'
   // précédement appelé.
   if($form->isSubmitted() && $form->isValid()) {
      $em->persist($recipe); // Ce n'est plus une simple màj
      $em->flush();
      $this->addFlash('success', 'La recette a bien été créée');
      return $this->redirectToRoute('recipe.index');
   }

   // Page permettant la saisie d'une nouvelle recette.
   // Aucune données à transmettre.
   return $this->render('recipe/create.html.twig', [
      'recipeForm' => $form
    ]);
}
```

<br>

La page html *TutoSymfony/templates/recipe/**create.html.twig*** permettra la création du nouvel enregistrement

```html
<h1>Création d'une nouvelle recette</h1>
{{ form(recipeForm) }}
```

<br>

Pour le moment les dates et le *slug* doivent être renseignées.

![17](pic/17.png)

<br>

On souhaite ne plus avoir à saisir les champs *createdAt* et *updatedAt* lors de la création d'un nouvel enregistrement.

```php
#[Route('/recipe/create', name: 'recipe.create')]
public function create( Request $request, 
                        EntityManagerInterface $em): Response {
   // Création d'un objet Recipe permetant 
   // l'intégration d'un nouvel enregistrement.
   $recipe = new Recipe();
   ...

   if($form->isSubmitted() && $form->isValid()) {
      // Mise à jour des dates via la nouvel instance '$recipe'
      $recipe->setCreatedAt(new \DateTimeImmutable());
      $recipe->setUpdatedAt(new \DateTimeImmutable());
      ...
   }
   ...
}
```
<br>

On effectuera la même opération pour la mise à jour.

```php
$recipe->setCreatedAt(new \DateTimeImmutable());
```




<br>

On supprime les champs *createdAt* et *updatedAt* du formulaire afin de les supprimer de la page web.

```php
class RecipeType extends AbstractType {
    public function buildForm(FormBuilderInterface $builder, array $options): void  {
      $builder
         ->add('title', TextType::class, ['label' => 'Titre', 'empty_data' => ''])
         ->add('slug', TextType::class, ['label' => 'Path', 'empty_data' => ''])
         ->add('content', TextareaType::class, ['label' => 'Contenu', 'empty_data' => ''])
         ->add('duration', IntegerType::class)
         ->add('save', SubmitType::class, ['label' => 'Envoyer']);
   }
   ...
}
```

<br>

:warning: Les champ ***createdAt*** et ***updatedAt*** ont été supprimé du formulaire. &nbsp;&#8640;&nbsp; Ne pas oublier de les supprimer du fichier *TutoSymfony/templates/recipe/**edit.html.twig***.

```html
<h1>Mise à jour {{ recipeData.title }}</h1>
{{ form_start(recipeForm) }}
<div class="d-flex gap-2">
	{{ form_row(recipeForm.title) }}
	{{ form_row(recipeForm.slug) }}
</div>
<div class="d-flex flex-column">
	{{ form_row(recipeForm.content) }}
	{{ form_row(recipeForm.duration) }}
</div>
{{ form_end(recipeForm) }}
```

<br>

Il ne reste que les quatre champs au niveau de l'édition de la recette.

![20](pic/20.png)



### Mise à jour automatique du champ *slug/Path*

Le mise à jour du champ *slug* doit être effectué à partir du champ *title* avant la soumission du formulaire. Ce traitement peut-être obtenue par l'utilisation d'un évènement au niveau du formulaire.
Le champ *slug* étant automatiquement mis à jour, ajouter l'option `'required' => false`;

```php
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Form\Event\PreSubmitEvent;
use Symfony\Component\String\Slugger\AsciiSlugger;
...

class RecipeType extends AbstractType {
    public function buildForm(FormBuilderInterface $builder, array $options): void  {
      $builder
         ->add('title', TextType::class, ['label' => 'Titre', 'empty_data' => ''])
         ->add('slug', TextType::class, ['label' => 'Path', 'required' => false, 'empty_data' => ''])
         ->add('content', TextareaType::class, ['label' => 'Contenu', 'empty_data' => ''])
         ->add('duration', IntegerType::class)
         ->add('save', SubmitType::class, ['label' => 'Envoyer'])
         // Le 'slug' est construit avec le 'title' déjà présent sur la page web
         // et mise à jour sur la page web avant soumission => PRE_SUBMIT 
         ->addEventListener(FormEvents::PRE_SUBMIT, $this->factory->autoSlug('title'));
   }

   function autoSlug(PreSubmitEvent $event): void {

      // Récupération de données (cf. trace ci-dessous)
      $data = $event->getData();
      // dd($data);
      
      // La mise à jour du champ 'slug' avec le champ 'title' 
      // est effectués via un tableau (cf. trace ci-dessous)
      if (empty($data['slug'])) {
         $slugger = new AsciiSlugger();
         $data['slug'] = strtolower($slugger->slug($data['title']));
         $event->setData($data);
      }
   }
   ...
}
```

Les traces renvoyées par le `dd($data)` sont les suivantes :

![21](pic/21.png)


### Mise à jour automatique des champs *createdAt* et *updatedAt*

Les évènements nous permettent de déporter la mise à jour des champs ***createdAt*** et ***updatedAt*** du *controller* vers le formulaire.

```php
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Form\Event\PostSubmitEvent;
...

class RecipeType extends AbstractType {
    public function buildForm(FormBuilderInterface $builder, array $options): void  {
      $builder
         ->add('title', TextType::class, ['label' => 'Titre', 'empty_data' => ''])
         ->add('slug', TextType::class, ['label' => 'Path', 'required' => false, 'empty_data' => ''])
         ->add('content', TextareaType::class, ['label' => 'Contenu', 'empty_data' => ''])
         ->add('duration', IntegerType::class)
         ->add('save', SubmitType::class, ['label' => 'Envoyer'])
         ->addEventListener(FormEvents::PRE_SUBMIT, $this->factory->autoSlug('title'))
         // Les dates ne sont plus définies dans ce formulaire => PRE_SUBMIT 
         ->addEventListener(FormEvents::POST_SUBMIT, $this->attachTimestamps(...));
   }

   function attachTimestamps(PostSubmitEvent $event): void {

      // Récupération de données (cf. trace ci-dessous)
      $data = $event->getData();

      if($data instanceof Recipe) {
         $data->setUpdatedAt(new \DateTimeImmutable());
         // Nouvelle enregistrement
         if (!$data->getId()) {
             $data->setCreatedAt(new \DateTimeImmutable());
         }

         // /!\ Pas de '$event->setData($data)'
      }
   }
   ...
}
```
Les traces renvoyées par le `dd($data)` sont les suivantes :

![22](pic/22.png)


<br>

On peut supprimer les mise à jour des dates au niveau du *controller*.

```php
$recipe->setCreatedAt(new \DateTimeImmutable());
$recipe->setUpdatedAt(new \DateTimeImmutable());
```

<br>


## Suppression d'une recette

Modifier le *controller* pour prendre en charge la suppression d'une recette.

```php
#[Route('/recipe/delete/{id}', name: 'recipe.delete', methods: ['DELETE'])]
// Récupération par la 'Primary key' dans l'instance '$recipe'
public function delete(Recipe $recipe, EntityManagerInterface $em): Response {
   $em->remove($recipe);
   $em->flush();
   $this->addFlash('success', 'La recette a bien été supprimée');
   return $this->redirectToRoute('recipe.index');
}
```

<br>

Mettre à jour le fichier de configuration *TutoSymfony/config/packages/**framework.yaml*** 
afin d'autoriser l'utilisation de la méthode **DELETE** dans le *controller* 
&nbsp;&#8640;&nbsp; `#[Route('/recipe/delete/{id}', name: 'recipe.delete', methods: ['DELETE'])]` 

```yaml
framework:
    secret: '%env(APP_SECRET)%'
    session: true
    # Autorise l'utilisation de la methods 'DELETE' par le controller :
    http_method_override: true
```

<br>

La suppression d'une recette via un ***SUBMIT*** permet l'utilisation de la méthode ***DELETE*** via un champ caché et empêche l'utilisation d'un lien.<br>
:warning: L'action dans la balise `<form>` est configuré avec un ***post***.

```html
<h1 class="mb-3">Recipes</h1>
<!-- Nouvelle recette -->
<p class="mb-1">
	<a class="btn btn-primary btn-sm" href="{{ path("recipe.create") }}">Nouvelle recette</a>
</p>
<!-- Liste des recettes -->
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
            <!-- Visualisation d'une recette -->
				<a href="{{ url('recipe.show', { id: recipe.id }) }}">{{ recipe.title }}</a>
			</td>
			<td>
				<div class="d-flex gap-1">
               <!-- Edition d'une recette -->
					<a class="btn btn-primary btn-sm" href="{{ path("recipe.edit", {id: recipe.id }) }}">Editer</a>
               <!--  Suppression d'une recette -->
					<form action="{{ path("recipe.delete", {id: recipe.id }) }}" method="post">
						<!-- Simulation d'une requête DELETE -->
                  <input type="hidden" name="_method" value="DELETE">
						<button type="submit" class="btn btn-danger btn-sm">Supprimer</button>
					</form>
				</div>
			</td>
		</tr>
	{% endfor %}
</table>
```

<br>

On peut à présent consulter, modifier, ajouter et supprimer une recette.

![19](pic/19.png)

<br>