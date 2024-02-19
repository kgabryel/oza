<?php

namespace App\Form;

use App\Config\Form\QuickListConfig;
use App\Config\Message\Error\QuickListErrors;
use App\Field\QuickListPosition;
use App\Field\Wysiwyg;
use App\Model\Form\QuickList;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\Type;

class QuickListForm extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder->add('name', TextType::class, [
            'label' => QuickListConfig::NAME_LABEL,
            'attr' => [
                'maxlength' => QuickListConfig::NAME_MAX_LENGTH
            ],
            'constraints' => [
                new Type([
                    'type' => 'string',
                    'message' => QuickListErrors::INVALID_VALUE
                ]),
                new Length([
                    'max' => QuickListConfig::NAME_MAX_LENGTH,
                    'maxMessage' => QuickListErrors::INVALID_NAME
                ])
            ]
        ])
            ->add('note', Wysiwyg::class, [
                'label' => QuickListConfig::NOTE_LABEL,
                'constraints' => [
                    new Type([
                        'type' => 'string',
                        'message' => QuickListErrors::INVALID_VALUE
                    ])
                ]
            ])
            ->add(
                'positions',
                CollectionType::class,
                [
                    'entry_type' => QuickListPosition::class,
                    'allow_add' => true,
                ]
            );
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => QuickList::class
        ]);
    }
}
