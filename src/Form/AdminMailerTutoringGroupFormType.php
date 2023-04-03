<?php

namespace App\Form;

use App\Entity\User;
use App\Repository\UserRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class AdminMailerTutoringGroupFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('users', EntityType::class, [
                'required' => true,
                'label' => 'E-mails des destinataires (appuyer sur CTRL+A pour tout sélectionner d\'un coup)',
                'multiple' => true,
                'class' => User::class,
                'query_builder' => function (UserRepository $userRepository) {
                    return $userRepository->findAllVolunteerEmailAccepted();
                },
                'choice_label' => 'email',
                'attr' => [
                    'class'=> 'form-control',
                ],
            ])
//            ->add('cc_users', EntityType::class, [
//                'required' => true,
//                'label' => 'E-mails des destinataires en copie cachée',
//                'multiple' => true,
//                'class' => User::class,
//                'query_builder' => function (UserRepository $userRepository) {
//                    return $userRepository->findAllUserEmail();
//                },
//                'choice_label' => 'email',
//                'attr' => [
//                    'class'=> 'form-control',
//                ],
//            ])
            ->add('subject',TextType::class, [
                'required' => true,
                'label' => 'Sujet de l\'e-mail',
                'attr' => [
                    'class'=> 'form-control',
                ],
            ])
            ->add('content',TextareaType::class, [
                'required' => true,
                'label' => 'Contenu',
                'attr' => [
                    'class'=> 'summernote',
                ],
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([

        ]);
    }
}