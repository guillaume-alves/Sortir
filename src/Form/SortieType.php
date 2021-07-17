<?php

namespace App\Form;

use App\Entity\Etat;
use App\Entity\Lieu;
use App\Entity\Sortie;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class SortieType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('nom')
            ->add('dateHeureDebut',DateTimeType::class, array(
                'label' => 'Date et heure de début',
                'widget' => 'single_text',
                'empty_data' => '',
                'attr' => array('class' => 'form-control', 'style' => 'line-height: 20px;')))
            ->add('dateLimiteInscription',DateTimeType::class, array(
                'label' => "Date limite d'inscription",
                'widget' => 'single_text',
                'empty_data' => '',
                'attr' => array('class' => 'form-control', 'style' => 'line-height: 20px;')))
            ->add('duree', IntegerType::class, [
                'label' => 'Durée (minutes)'
            ])
            ->add('nbInscriptionsMax', IntegerType::class, [
                'label' => 'Nombre de places'])
            ->add('infosSortie', TextareaType::class, [
                'label' => 'Description et infos'])
            ->add('lieu', EntityType::class, [
                'class' => Lieu::class,
                'mapped' => true,
                'placeholder' => 'Selectionnez le lieu',
            ])
            ->add('etat',EntityType::class,[
                'class' => Etat::class,
                'mapped' => true,
                'placeholder' => "Selectionnez l'état",
            ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Sortie::class,
        ]);
    }
}
