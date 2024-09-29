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
