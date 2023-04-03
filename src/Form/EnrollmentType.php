<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class EnrollmentType extends AbstractType
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
