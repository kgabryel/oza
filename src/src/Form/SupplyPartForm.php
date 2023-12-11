<?php

namespace App\Form;

use App\Config\Form\SupplyConfig;
use App\Config\Message\Error\SupplyErrors;
use App\Entity\Product;
use App\Entity\Unit;
use App\Field\MaterialDate;
use App\Field\MaterialSwitch;
use App\Field\Wysiwyg;
use App\Model\Form\SupplyPart;
use App\Utils\UnitUtils;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\GreaterThanOrEqual;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Type;

class SupplyPartForm extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $units = UnitUtils::getUnitList($options['supply']->getGroup()->getUnit());
        $products = $options['supply']->getGroup()->getProducts()->toArray();
        $builder->add('amount', NumberType::class, [
            'label' => SupplyConfig::AMOUNT_LABEL,
            'constraints' => [
                new NotBlank([
                    'message' => SupplyErrors::AMOUNT_MISSING
                ]),
                new Type([
                    'type' => 'float',
                    'message' => SupplyErrors::INVALID_VALUE
                ]),
                new GreaterThanOrEqual([
                    'value' => 0,
                    'message' => SupplyErrors::AMOUNT_TOO_SMALL
                ])
            ]
        ])
            ->add('part', NumberType::class, [
                'label' => SupplyConfig::PART_LABEL,
                'constraints' => [
                    new NotBlank([
                        'message' => SupplyErrors::PART_MISSING
                    ]),
                    new GreaterThanOrEqual([
                        'value' => 0,
                        'message' => SupplyErrors::PART_TOO_SMALL
                    ])
                ],
                'data' => 1,
                'attr' => [
                    'step' => 1
                ]
            ])
            ->add('description', Wysiwyg::class, [
                'label' => SupplyConfig::DESCRIPTION_LABEL
            ])
            ->add('unit', EntityType::class, [
                'label' => SupplyConfig::UNIT_LABEL,
                'choices' => $units,
                'class' => Unit::class,
                'invalid_message' => SupplyErrors::INVALID_UNIT,
                'constraints' => [
                    new NotBlank([
                        'message' => SupplyErrors::UNIT_MISSING
                    ]),
                ]
            ])
            ->add('product', EntityType::class, [
                'label' => SupplyConfig::PRODUCT_LABEL,
                'attr' => [
                    'class' => 'Product'
                ],
                'choices' => $products,
                'class' => Product::class,
                'invalid_message' => SupplyErrors::INVALID_PRODUCT
            ])
            ->add('open', MaterialSwitch::class, [
                'label' => SupplyConfig::OPEN_LABEL
            ])
            ->add('dateOfConsumption', MaterialDate::class, [
                'label' => SupplyConfig::DATE_OF_CONSUMPTION
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => SupplyPart::class,
            'supply' => null
        ]);
    }
}
