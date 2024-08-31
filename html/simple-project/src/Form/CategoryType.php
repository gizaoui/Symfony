<?php

namespace App\Form;

use App\Entity\Category;
use App\Entity\Recipe;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CategoryType extends AbstractType {
    
    public function __construct(private FormListenerFactory $factory)
    {
    }
    
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder->add('name', TextType::class, ['label' => 'Nom', 'empty_data' => ''])
            ->add('slug', TextType::class, ['label' => 'Slug', 'empty_data' => '', 'required' => false])
            ->add('recipes', EntityType::class, ['choice_label' => 'title', 'multiple' => true, 'class' => Recipe::class, 'by_reference' => false, 'required' => false])
            ->add('save', SubmitType::class, ['label' => 'Envoyer'])
            ->addEventListener(FormEvents::PRE_SUBMIT, $this->factory->autoSlug('name'))
            ->addEventListener(FormEvents::POST_SUBMIT, $this->factory->timestamps());
    }
    
    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults(['data_class' => Category::class]);
    }
}
