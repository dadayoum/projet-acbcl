<?php

namespace App\Form;

use App\Entity\Tutoring;
use App\Entity\User;
use App\Repository\UserRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class TutoringType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
//            ->add('createdAt', DateType::class, [
//                'required' => true,
//                'label' => 'Demande créée le',
//                'widget' => 'single_text',
//                'attr' => [
//                    'class'=> 'form-control',
//                ]
//            ])
            ->add('userTutoring', EntityType::class, [
                'required' => true,
                'label' => 'Email du demandeur',
                'class' => User::class,
                'query_builder' => function (UserRepository $userRepository) {
                    return $userRepository->findAllTutoringEmail();
                },
                'choice_label' => 'email',
                'attr' => [
                    'class'=> 'form-control',
                ],
            ])
            ->add('otherMembers',TextareaType::class, [
                'required' => false,
                'label' => 'Autres membres de la famille concernés par cette demande',
                'attr' => [
                    'class'=> 'form-control',
                ],
            ])
            ->add('status', ChoiceType::class, [
                'required' => true,
                'label' => 'Statut',
                'choices' => [
                    'En cours' => 'IN_PROGRESS',
                    'Terminée' => 'COMPLETE',
                ],
                'attr' => [
                    'class'=> 'form-control',
                ],
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Tutoring::class,
        ]);
    }
}
