<?php

namespace App\Form;

use App\Entity\Volunteer;
use App\Entity\User;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class VolunteerEditType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('isValidated', ChoiceType::class, [
                'required' => true,
                'label' => 'Réponse',
                'choices' => [
                    'Refuser' => false,
                    'Accepter' => true,
                ],
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
            'data_class' => Volunteer::class,
        ]);
    }
}
