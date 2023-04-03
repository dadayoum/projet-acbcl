<?php

namespace App\Form;

use App\Entity\Activity;
use App\Entity\SessionEvent;
use App\Entity\Course;
use App\Entity\Conference;
use App\Repository\ActivityRepository;
use App\Repository\ConferenceRepository;
use App\Repository\CourseRepository;
use App\Repository\UserRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\CallbackTransformer;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class SessionEventType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('startAt', DateTimeType::class, [
                'required' => true,
                'input' => 'datetime_immutable',
                'label' => 'Commence le',
                'widget' => 'single_text',
                'attr' => [
                    'class'=> 'form-control',
                ]
            ])
            ->add('endAt', DateTimeType::class, [
                'required' => true,
                'input' => 'datetime_immutable',
                'label' => 'Termine le',
                'widget' => 'single_text',
                'attr' => [
                    'class'=> 'form-control',
                ]
            ])
            ->add('location',TextType::class, [
                'required' => false,
                'label' => 'Adresse du lieu',
                'attr' => [
                    'class'=> 'form-control',
                ],
            ])
            ->add('referent',TextType::class, [
                'required' => false,
                'label' => 'Référent',
                'attr' => [
                    'class'=> 'form-control',
                ],
            ])
            ->add('referentContact',TextType::class, [
                'required' => false,
                'label' => 'Contact du référent (e-mail ou n° de téléphone)',
                'attr' => [
                    'class'=> 'form-control',
                ],
            ])
            ->add('course', EntityType::class, [
                'label' => 'Formation',
                'required' => false,
                'class' => Course::class,
                'query_builder' => function (CourseRepository $courseRepository) {
                    return $courseRepository->findAllCourseName();
                },
                'choice_label' => 'name',
                'attr' => [
                    'class'=> 'form-control',
                ],
            ])
            ->add('activity', EntityType::class, [
                'label' => 'Activité',
                'required' => false,
                'class' => Activity::class,
                'query_builder' => function (ActivityRepository $activityRepository) {
                    return $activityRepository->findAllActivityName();
                },
                'choice_label' => 'name',
                'attr' => [
                    'class'=> 'form-control',
                ],
            ])
            ->add('conference', EntityType::class, [
                'label' => 'Conférence',
                'required' => false,
                'class' => Conference::class,
                'query_builder' => function (ConferenceRepository $conferenceRepository) {
                    return $conferenceRepository->findAllConferenceName();
                },
                'choice_label' => 'name',
                'attr' => [
                    'class'=> 'form-control',
                ],
            ])
            ->add('price',NumberType::class, [
                'required' => true,
                'label' => 'Prix (si gratuit -> 0)',
                'attr' => [
                    'class'=> 'form-control',
                ],
            ])
        ;

//        $builder->get('referent')
//            ->addModelTransformer(new CallbackTransformer(
//                function ($rolesArray) {
//                    return count($rolesArray) ? $rolesArray[0] : null;
//                },
//                function ($rolesString){
//                    return [$rolesString];
//                }
//            ));
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => SessionEvent::class,
        ]);
    }
}
