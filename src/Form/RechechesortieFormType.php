<?php

namespace App\Form;

use App\Entity\Campus;
use App\Entity\CritereRechercheSorties;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class RechechesortieFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('nomcampus', EntityType::class, [
                'class' => Campus::class,
                'mapped' => true,
                'placeholder' => 'Selectionnez votre campus',
            ])
            ->add('mot')
            ->add('datedebut')
            ->add('datefin')
            ->add('jorganise', CheckboxType::class, [
                'label' => 'Sorties dont je suis lorganisateur',
                'required' => false,
            ])
            ->add('inscrit', CheckboxType::class, [
                'label' => 'Soties auxquelles je suis incrit/e',
                'required' => false,
            ])
            ->add('noninscrit', CheckboxType::class, [
                'label' => 'Soties auxquelles je ne suis pas incrit/e',
                'required' => false,
            ])
            ->add('sortiesold', CheckboxType::class, [
                'label' => 'Sorties passées',
                'required' => false,
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => CritereRechercheSorties::class,
            'method' => 'GET',
            'csrf_protection' => false
        ]);
    }
}
