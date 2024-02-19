<?php

namespace App\Form;

use App\Config\Form\ShoppingListConfig;
use App\Config\Message\Error\ShoppingListErrors;
use App\Field\ShoppingListPosition;
use App\Field\Wysiwyg;
use App\Model\Form\ShoppingList;
use App\Validator\CorrectUnit\CorrectUnit;
use App\Validator\PositionExists\PositionExists;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\Type;

class ShoppingListForm extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder->add('name', TextType::class, [
            'label' => ShoppingListConfig::NAME_LABEL,
            'attr' => [
                'maxlength' => ShoppingListConfig::NAME_MAX_LENGTH
            ],
            'constraints' => [
                new Type([
                    'type' => 'string',
                    'message' => ShoppingListErrors::INVALID_VALUE
                ]),
                new Length([
                    'max' => ShoppingListConfig::NAME_MAX_LENGTH,
                    'maxMessage' => ShoppingListErrors::NAME_TOO_LONG
                ])
            ]
        ])
            ->add('note', Wysiwyg::class, [
                'label' => ShoppingListConfig::NOTE_LABEL,
                'constraints' => [
                    new Type([
                        'type' => 'string',
                        'message' => ShoppingListErrors::INVALID_VALUE
                    ])
                ]
            ])
            ->add(
                'positions',
                CollectionType::class,
                [
                    'entry_type' => ShoppingListPosition::class,
                    'allow_add' => true,
                    'entry_options' => [
                        'constraints' => [
                            new PositionExists(),
                            new CorrectUnit()
                        ]
                    ]
                ]
            );
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => ShoppingList::class
        ]);
    }
}
