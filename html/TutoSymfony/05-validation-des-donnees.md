# Validation des données


```php
use Symfony\Component\Validator\Constraints\Length;
...

class RecipeType extends AbstractType {
    public function buildForm(FormBuilderInterface $builder, array $options): void  {
      $builder
         ->add('title', TextType::class, ['label' => 'Titre'])
         ->add('slug', TextType::class, [ 'label' => 'Path', 
                                          'required' => false, 
                                          'constraints' => new Length(min:10) ])
         ->add('content', TextareaType::class, ['label' => 'Contenu'])
         ->add('duration', IntegerType::class)
         ->add('save', SubmitType::class, ['label' => 'Envoyer'])
         ->addEventListener(FormEvents::PRE_SUBMIT, $this->factory->autoSlug('title'))
         ->addEventListener(FormEvents::POST_SUBMIT, $this->attachTimestamps(...));
   }
   ...
}
```

La soumission nous renvoie sur page avec l'afficahge de l'erreur. 

![23](pic/23.png)

