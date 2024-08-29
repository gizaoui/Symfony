<?php

namespace App\Form;

use App\Entity\Recipe;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Form\Event\PostSubmitEvent;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\OptionsResolver\OptionsResolver;

class RecipeType extends AbstractType
{

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
    {
        $builder->add('title', TextType::class, ['label' => 'Titre', 'empty_data' => ''])
            ->add('slug', TextType::class, ['label' => 'Path'])
            ->add('content', TextareaType::class, ['label' => 'Contenu', 'empty_data' => ''])
            ->add('duration', IntegerType::class)
            ->add('save', SubmitType::class, ['label' => 'Envoyer'])
            ->addEventListener(FormEvents::POST_SUBMIT, $this->attachTimestamps(...));
    }

    public function attachTimestamps(PostSubmitEvent $event): void
    {
        $data = $event->getData();
        if ($data instanceof Recipe) {
            $data->setUpdateAt(new \DateTimeImmutable());
            if (! $data->getId()) { // Nouvel enregistrement
                $data->setCreatedAt(new \DateTimeImmutable());
            }
        } else {
            return;
        }
    }


    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Recipe::class,
            # Les groupes permettent le désactivation des règles de validation
            'validation_groups' => ['Default', 'Extra']
        ]);
    }
}
