<?php

namespace App\Form;

use App\Entity\Article;
use App\Entity\ArticleType;
use App\Repository\ArticleTypeRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ArticleEditType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {

        $builder
            ->add('title',TextType::class, [
                'required' => true,
                'label' => 'Titre de l\'article',
                'attr' => [
                    'class'=> 'form-control',
                ],
            ])
            ->add('content',TextareaType::class, [
                'required' => true,
                'label' => 'Texte',
                'attr' => [
                    'class'=> 'summernote',
                ],
            ])
            ->add('articleType', EntityType::class, [
                'label' => 'Type d\'article',
                'required' => true,
                'class' => ArticleType::class,
                'query_builder' => function (ArticleTypeRepository $articleTypeRepository) {
                    return $articleTypeRepository->findAllArticleTypeName();
                },
                'choice_label' => 'name',
                'attr' => [
                    'class'=> 'form-control',
                ],
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Article::class,
        ]);
    }
}
