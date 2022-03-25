<?php

namespace App\Form;

use App\DTO\SearchAuthor;
use App\Entity\Author;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType as TypeIntegerType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType as TypeTextType;

class SearchAuthorType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name', TypeTextType::class, [
                'label' => 'Nom :',
                'required' => false,
            ])
            ->add('limit', TypeIntegerType::class, [
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
                    'Nom' => 'name'
                ],
            ])
            ->add('direction', ChoiceType::class, [
                'label' => 'Sens du trie :',
                'choices' => [
                    'Croissant' => 'ASC',
                    'Décroissant' => 'DESC',
                ],
            ])
            ->add('submit', SubmitType::class, [
                'label' => 'Rechercher',
            ]);
            ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => SearchAuthor::class,
            'method' => 'GET',
            'csrf_protection' => false,
        ]);
    }
}
