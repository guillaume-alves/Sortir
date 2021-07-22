<?php

namespace App\Form;

use App\Entity\Campus;
use App\Entity\Participant;
use App\Repository\CampusRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\IsTrue;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Vich\UploaderBundle\Form\Type\VichImageType;

class RegistrationFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
            $builder
                ->add('nom')
                ->add('prenom')
                ->add('email')
                ->add('telephone');

        if ($options['firstRegistration']) {
            $builder
                ->add('campus', EntityType::class, [
                    'class' => Campus::class,
                    'mapped' => true,
                    'placeholder' => 'Selectionnez votre campus',
                    'constraints' => [
                        new NotBlank([
                            'message' => 'Please enter a campus',
                        ])]])
                ->add('agreeTerms', CheckboxType::class, [
                    'mapped' => false,
                    'label' => "J'accepte les CGU.",
                    'constraints' => [
                        new IsTrue([
                            'message' => 'Veuillez accepter les CGU.',
                        ]),
                    ],
                ])
                ->add('plainPassword', RepeatedType::class, [
                    // instead of being set onto the object directly,
                    // this is read and encoded in the controller
                    'type' => PasswordType::class,
                    'invalid_message' => 'The password fields must match.',
                    'first_options'  => ['label' => 'Password'],
                    'second_options' => ['label' => 'Repeat Password'],
                    //Originel sans RepeatType
                    'required' => true,
                    'mapped' => false,
                    'attr' => ['autocomplete' => 'new-password'],
                    'constraints' => [
                        new NotBlank([
                            'message' => 'Please enter a password',
                        ]),
                        new Length([
                            'min' => 6,
                            'minMessage' => 'Your password should be at least {{ limit }} characters',
                            // max length allowed by Symfony for security reasons
                            'max' => 4096,
                        ]),
                    ],
                ])
            ;
        }
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Participant::class,
            'firstRegistration' => true
        ]);
    }
}
