<?php

namespace App\Form;

use App\DTO\SearchBookAdmin;
use App\Entity\Book;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class SearchBookAdminType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('title', TextType::class, [
                'label' => 'Titre :',
                'required' => false,
            ])
            ->add('limit', IntegerType::class, [
                'label' => 'Limite :',
                'required' => true,
                'attr' => [
                    'min' => 5,
                    'max' => 50,
                    'step' => 5,
                ]
            ])
            ->add('page', NumberType::class, [
                'label' => 'Page :',
                'required' => true,
            ])
            ->add('sortBy', ChoiceType::class, [
                'label' => 'Trier par :',
                'choices' => [
                    'Identifiant' => 'id',
                    'Titre' => 'title',
                    'Prix' => 'price',
                ],
            ])
            ->add('direction', ChoiceType::class, [
                'label' => 'Sens du trie :',
                'choices' => [
                    'Croissant' => 'ASC',
                    'Décroissant' => 'DESC',
                ],
            ])
            ->add('authorName', TextType::class, [
                'label' => 'Nom de l\'auteur :',
                'required' => false,
            ])
            ->add('prixMin', IntegerType::class, [
                'label' => 'Prix Mininum :',
                'attr' => [
                    'min' => 0,
                    'max' => 9999,
                    'step' => 1,
                ]
            ])
            ->add('prixMax', IntegerType::class, [
                'label' => 'Prix Maximum :',
                'attr' => [
                    'min' => 1,
                    'max' => 9999,
                    'step' => 1,
                ]
            ])
            ->add('submit', SubmitType::class, [
                'label' => 'Rechercher',
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => SearchBookAdmin::class,
            'method' => 'GET',
            'csrf_protection' => false,
        ]);
    }
}
