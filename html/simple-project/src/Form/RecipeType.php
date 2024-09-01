<?php

namespace App\Form;

use App\Entity\Recipe;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use App\Entity\Category;

class RecipeType extends AbstractType {
    
    public function __construct(private FormListenerFactory $factory)
    {
    }
    
    /**
     * - /!\ le TextType dans 'Symfony\Component\Form\Extension\Core\Type\TextType' (php bin/console debug:form)
     * - Suppression de l'affichage dans les formulaires :
     *    -> add ( 'createdAt', DateTimeType::class )
     *    -> add ( 'updateAt', DateTimeType::class )
     * - La validation des formulaire peut-être placé au niveau de ce fichier 
     *    ->add('slug', TextType::class, [
     *               'label' => 'Path', 
     *               'constraints' => new Sequentially( [
     *                  new Length(min: 5),
     *                  new Regex('/^[a-z0-9]+(?:-[a-z0-9]+)*$/')
     *               ])
     *       ])
     * */
    public function buildForm(FormBuilderInterface $builder, array $options): void
    { # @formatter:off
        $builder->add('title', TextType::class, ['label' => 'Titre', 'empty_data' => ''])
            ->add('slug', TextType::class, ['label' => 'Path', 'required' => false])
            ->add('category', EntityType::class, [
                    'choice_label' => 'name', // Champ de l'entité Category
                    'class' => Category::class]) // Entité Category
            ->add('content', TextareaType::class, ['label' => 'Contenu', 'empty_data' => ''])
            ->add('duration', IntegerType::class)
            ->add('save', SubmitType::class, ['label' => 'Envoyer'])
            ->addEventListener(FormEvents::PRE_SUBMIT, $this->factory->autoSlug('title'))
            ->addEventListener(FormEvents::POST_SUBMIT, $this->factory->timestamps());
            # @formatter:on
    }
    
    # Remplace le  ->addEventListener(FormEvents::POST_SUBMIT, $this->attachTimestamps(...))
    //     public function attachTimestamps(PostSubmitEvent $event): void
    //     {
    //         $data = $event->getData();
    //         if ($data instanceof Recipe)
    //         {
    //             $data->setUpdateAt(new \DateTimeImmutable());
    //             if (! $data->getId())
    //             { // Nouvel enregistrement
    //                 $data->setCreatedAt(new \DateTimeImmutable());
    //             }
    //         }
    //         else
    //         {
    //             return;
    //         }
    //     }
    //
    //
    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults(['data_class' => Recipe::class, 
                # Les groupes permettent le désactivation des règles de validation
                'validation_groups' => ['Default', 'Extra']]);
    }
}
