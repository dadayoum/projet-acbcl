<?php

namespace App\Form;

use App\Entity\SessionEvent;
use App\Entity\User;
use App\Repository\UserRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class SessionEventAddUserType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('users', EntityType::class, [
                'required' => true,
                'label' => 'Email du participant',
                'multiple' => true,
                'class' => User::class,
                'query_builder' => function (UserRepository $userRepository) {
                    return $userRepository->findAllUserEmail();
                },
                'choice_label' => 'email',
                'attr' => [
                    'class'=> 'form-control',
                ],
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => SessionEvent::class,
        ]);
    }
}
