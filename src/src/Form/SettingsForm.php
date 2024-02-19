<?php

namespace App\Form;

use App\Config\Form\SettingsConfig as SettingsFormConfig;
use App\Config\Message\Error\SettingsErrors;
use App\Config\Settings as SettingsConfig;
use App\Config\ShoppingListLayoutType;
use App\Field\MaterialSwitch;
use App\Model\Form\Settings;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Positive;

class SettingsForm extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder->add(SettingsConfig::PAGINATION_COUNT, ChoiceType::class, [
            'label' => SettingsFormConfig::PAGINATION_COUNT_LABEL,
            'invalid_message' => SettingsErrors::INVALID_VALUE,
            'choices' => [
                '5' => 5,
                '10' => 10,
                '15' => 15
            ],
            'constraints' => [
                new NotBlank([
                    'message' => SettingsErrors::INVALID_VALUE
                ])
            ]
        ])
            ->add(SettingsConfig::HIDE_BOUGHT, MaterialSwitch::class, [
                'label' => SettingsFormConfig::HIDE_BOUGHT_LABEL
            ])
            ->add(SettingsConfig::DELETE_UNCHECKED_POSITIONS, MaterialSwitch::class, [
                'label' => SettingsFormConfig::DELETE_UNCHECKED_POSITIONS_LABEL
            ])
            ->add(SettingsConfig::DELETE_UNCHECKED_POSITIONS_QUICK, MaterialSwitch::class, [
                'label' => SettingsFormConfig::DELETE_UNCHECKED_POSITIONS_QUICK_LABEL
            ])
            ->add(SettingsConfig::MAX_SHOPS_GROUP_COUNT, NumberType::class, [
                'label' => SettingsFormConfig::MAX_SHOPS_GROUP_COUNT_LABEL,
                'constraints' => [
                    new Positive([
                        'message' => SettingsErrors::INVALID_COUNT
                    ])
                ]
            ])
            ->add(SettingsConfig::NEW_SHOPPING_DAYS, NumberType::class, [
                'label' => SettingsFormConfig::NEW_SHOPPING_DAYS_LABEL,
                'constraints' => [
                    new Positive([
                        'message' => SettingsErrors::INVALID_COUNT
                    ])
                ]
            ])
            ->add(SettingsConfig::CREATE_SUPPLY, MaterialSwitch::class, [
                'label' => SettingsFormConfig::CREATE_SUPPLY_LABEL
            ])
            ->add(SettingsConfig::DELETE_LISTS, MaterialSwitch::class, [
                'label' => SettingsFormConfig::DELETE_LISTS
            ])
            ->add(SettingsConfig::DELETE_LIST_DAYS, NumberType::class, [
                'label' => SettingsFormConfig::DELETE_LIST_DAYS,
                'constraints' => [
                    new Positive([
                        'message' => SettingsErrors::INVALID_COUNT
                    ])
                ]
            ])
            ->add(SettingsConfig::DELETE_QUICK_LISTS, MaterialSwitch::class, [
                'label' => SettingsFormConfig::DELETE_QUICK_LISTS
            ])
            ->add(SettingsConfig::DELETE_QUICK_LIST_DAYS, NumberType::class, [
                'label' => SettingsFormConfig::DELETE_QUICK_LIST_DAYS,
                'constraints' => [
                    new Positive([
                        'message' => SettingsErrors::INVALID_COUNT
                    ])
                ]
            ])
            ->add(SettingsConfig::AUTOCOMPLETE, MaterialSwitch::class, [
                'label' => SettingsFormConfig::AUTOCOMPLETE
            ])
            ->add(SettingsConfig::SHOOPING_LIST_LAYOUT_TYPE, ChoiceType::class, [
                'label' => SettingsFormConfig::SHOOPING_LIST_LAYOUT_TYPE_LABEL,
                'invalid_message' => SettingsErrors::INVALID_VALUE,
                'choices' => [
                    'Lista' => ShoppingListLayoutType::LIST,
                    'Kafelki' => ShoppingListLayoutType::GRID,
                ],
                'constraints' => [
                    new NotBlank([
                        'message' => SettingsErrors::INVALID_VALUE
                    ])
                ]
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Settings::class
        ]);
    }
}
