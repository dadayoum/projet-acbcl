<?php

namespace App\Form;


use App\Entity\Payment;
use App\Entity\SessionEvent;
use App\Entity\User;
use App\Repository\SessionEventRepository;
use App\Repository\UserRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class PaymentType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('paid', ChoiceType::class, [
                'required' => true,
                'label' => 'A payé',
                'choices' => [
                    'Non' => false,
                    'Oui' => true,
                ],
                'attr' => [
                    'class'=> 'form-control',
                ],
            ])
            ->add('sessionEvent', EntityType::class, [
                'label' => 'N° de la session',
                'required' => true,
                'class' => SessionEvent::class,
//                'mapped' => false,
                'query_builder' => function (SessionEventRepository $sessionEventRepository) {
//                    dd($sessionRepository->findAll());
                    return $sessionEventRepository->findAllSessionId();
                },
                'choice_label' => 'id',
                'attr' => [
                    'class'=> 'form-control',
                ],
            ])
            ->add('userPayment', EntityType::class, [
                'label' => 'Utilisateur',
                'required' => true,
                'class' => User::class,
                'query_builder' => function (UserRepository $userRepository) {
                    return $userRepository->findAllUserEmail();
                },
                'choice_label' => 'email',
                'attr' => [
                    'class'=> 'form-control',
                ],
            ])
            ->add('paymentType', ChoiceType::class, [
                'required' => true,
                'label' => 'Moyen de paiement',
                'choices' => [
                    'Non défini' => 'UNDEFINED',
                    'Chèque' => 'BANK_CHECK',
                    'Espèce' => 'CASH',
                    'Aucun' => 'FREE',
                    'Stripe' => 'STRIPE',
                    'Virement' => 'BANK_TRANSFER',
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
            'data_class' => Payment::class,
        ]);
    }
}
