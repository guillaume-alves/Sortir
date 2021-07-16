<?php

namespace App\Form;

use App\Entity\Campus;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class SearchType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('search', TextType::class, [
                'label' => 'Mots-clés'
            ])
            ->add('campus', EntityType::class, [
                'class' => Campus::class,
                'mapped' => true,
                'placeholder' => 'Selectionnez le lieu',
            ])
            ->add('organiser', CheckboxType::class, [
                'label' => 'organisateur',
                'required' => false
            ])
            ->add('registered', CheckboxType::class, [
                'label' => 'Inscrit',
                'required' => false
            ])
            ->add('notRegistered', CheckboxType::class, [
                'label' => 'Non inscrit',
                'required' => false
            ])
            ->add('passed', CheckboxType::class, [
                'label' => 'Passées',
                'required' => false
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            // Configure your form options here
        ]);
    }
}