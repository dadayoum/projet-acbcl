<?php

namespace App\Form;

use App\Entity\SessionEvent;
use App\Entity\User;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\CallbackTransformer;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class SessionEventRemoveUserType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $sessionEvent = $options['data'];
        $builder
            ->add('users', EntityType::class, [
                'required' => true,
                'label' => 'Email du participant',
                'multiple' => true,
                'class' => SessionEvent::class,
                'choices' => $sessionEvent->getUsers(),
                'choice_label' => function (User $user) {
                    return $user->getEmail();
                },
                'choice_value' => 'id',
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
