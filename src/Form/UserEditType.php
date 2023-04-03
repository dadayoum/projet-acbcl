<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\CallbackTransformer;
use Symfony\Component\OptionsResolver\OptionsResolver;

class UserEditType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('email',EmailType::class, [
                'required' => true,
                'label' => 'Email',
                'attr' => [
                    'class'=> 'form-control',
                ],
            ])
            ->add('roles', ChoiceType::class, [
                'required' => true,
                'multiple' => false,
                'expanded' => false,
                'choices' => [
                    'Utilisateur' => 'ROLE_USER',
                    'Administrateur' => 'ROLE_ADMIN',
                ],
                'attr' => [
                    'class'=> 'form-control',
                ],
            ])
//            ->add('password',TextType::class, [
//                'required' => true,
//                'label' => 'Mot de passe',
//                'attr' => [
//                    'class'=> 'form-control'
//                ],
//            ])
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
            ->add('birthday', DateType::class, [
                'required' => false,
                'label' => 'Date de naissance',
                'widget' => 'single_text',
                'attr' => [
                    'class'=> 'form-control',
                ]
            ])
            ->add('address',TextType::class, [
                'required' => false,
                'label' => 'Adresse',
                'attr' => [
                    'class'=> 'form-control',
                ],
            ])
            ->add('zipCode',TextType::class, [
                'required' => false,
                'label' => 'Code postal',
                'attr' => [
                    'class'=> 'form-control',
                ],
            ])
            ->add('city',TextType::class, [
                'required' => false,
                'label' => 'Ville',
                'attr' => [
                    'class'=> 'form-control',
                ],
            ])
            ->add('phoneNumber',TextType::class, [
                'required' => false,
                'label' => 'Numéro de téléphone',
                'attr' => [
                    'class'=> 'form-control',
                ],
            ])
            ->add('isVerified',ChoiceType::class, [
                'required' => true,
                'label' => 'Compte vérifié',
                'choices' => [
                    'Oui' => true,
                    'Non' => false,
                ],
                'attr' => [
                    'class'=> 'form-control',
                ],
            ])
        ;

        $builder->get('roles')
            ->addModelTransformer(new CallbackTransformer(
                function ($rolesArray) {
                    return count($rolesArray) ? $rolesArray[0] : null;
                },
                function ($rolesString){
                    return [$rolesString];
                }
            ));
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
