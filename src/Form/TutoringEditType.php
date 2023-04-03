<?php

namespace App\Form;

use App\Entity\Tutoring;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class TutoringEditType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('otherMembers',TextType::class, [
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
