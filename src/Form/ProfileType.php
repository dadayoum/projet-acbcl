<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;

class ProfileType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('firstName',TextType::class, [
                'required' => true,
                'label' => 'Prénom',
                'attr' => [
                    'class'=> 'form-control',
                ],
            ])
            ->add('lastName',TextType::class, [
                'required' => true,
                'label' => 'Nom',
                'attr' => [
                    'class'=> 'form-control',
                ],
            ])
            ->add('password', RepeatedType::class, [
                'type' => PasswordType::class,
                'invalid_message' => 'Les mots de passes ne correspondent pas.',
                'options' => ['attr' => ['class' => 'password-field form-control']],
                'required' => true,
                'first_options'  => ['label' => 'Mot de passe'],
                'second_options' => ['label' => 'Répéter le mot de passe'],
                'constraints' => [
                    new NotBlank([
                        'message' => 'Please enter a password',
                    ]),
                    new Length([
                        'min' => 6,
                        'minMessage' => 'Votre mot de passe doit comporter au moins {{ limit }} caractères',
                        // max length allowed by Symfony for security reasons
                        'max' => 4096,
                    ]),
                ],
            ])
            ->add('birthday', DateType::class, [
                'required' => true,
                'label' => 'Date de naissance',
                'widget' => 'single_text',
                'attr' => [
                    'class'=> 'form-control',
                ]
            ])
            ->add('address',TextType::class, [
                'required' => true,
                'label' => 'Adresse',
                'attr' => [
                    'class'=> 'form-control',
                ],
            ])
            ->add('zipCode',TextType::class, [
                'required' => true,
                'label' => 'Code postal',
                'attr' => [
                    'class'=> 'form-control',
                ],
            ])
            ->add('city',TextType::class, [
                'required' => true,
                'label' => 'Ville',
                'attr' => [
                    'class'=> 'form-control',
                ],
            ])
            ->add('phoneNumber',TextType::class, [
                'required' => true,
                'label' => 'Numéro de téléphone',
                'attr' => [
                    'class'=> 'form-control',
                ],
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
