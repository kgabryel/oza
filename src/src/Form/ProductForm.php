<?php

namespace App\Form;

use App\Config\Form\ProductConfig;
use App\Config\Message\Error\ProductErrors;
use App\Entity\Brand;
use App\Entity\ProductsGroup;
use App\Entity\Unit;
use App\Field\MaterialBarcode;
use App\Field\Wysiwyg;
use App\Model\Form\Product;
use App\Repository\BrandRepository;
use App\Repository\ProductRepository;
use App\Repository\ProductsGroupRepository;
use App\Repository\UnitRepository;
use App\Services\UserService;
use App\Validator\ConnectedUnit\ProductConnectedUnit;
use App\Validator\SameUnits\SameUnits;
use App\Validator\UniqueForUser\UniqueForUser;
use App\Validator\UniqueProductName;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Callback;
use Symfony\Component\Validator\Constraints\Count;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Positive;
use Symfony\Component\Validator\Constraints\Type;
use Symfony\Component\Validator\Context\ExecutionContext;

class ProductForm extends UserForm
{
    private array $brands;
    private ProductRepository $productRepository;
    private array $productsGroups;
    private array $units;

    public function __construct(
        UserService $userService,
        ProductsGroupRepository $productsGroupRepository,
        ProductRepository $productRepository,
        UnitRepository $unitRepository,
        BrandRepository $brandRepository
    ) {
        parent::__construct($userService);
        $this->productRepository = $productRepository;
        $this->units = $unitRepository->findForUser($this->user);
        $this->productsGroups = $productsGroupRepository->findBy([
            'user' => $this->user
        ]);
        $this->brands = $brandRepository->findForUser($this->user);
    }

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder->add('name', TextType::class, [
            'label' => ProductConfig::NAME_LABEL,
            'attr' => [
                'maxlength' => ProductConfig::NAME_MAX_LENGTH
            ],
            'constraints' => [
                new NotBlank([
                    'message' => ProductErrors::NAME_MISSING
                ]),
                new Type([
                    'type' => 'string',
                    'message' => ProductErrors::INVALID_VALUE
                ]),
                new Length([
                    'max' => ProductConfig::NAME_MAX_LENGTH,
                    'maxMessage' => ProductErrors::NAME_TOO_LONG
                ]),
                new Callback(function($value, ExecutionContext $context) {
                    $validator = new UniqueProductName(
                        $context,
                        ProductErrors::NAME_WITH_BRAND_IN_USE,
                        ProductErrors::NAME_WITHOUT_BRAND_IN_USE,
                        $this->productRepository,
                        $this->user
                    );
                    $validator->validate($value);
                })
            ]
        ])
            ->add('note', Wysiwyg::class, [
                'label' => ProductConfig::DESCRIPTION_LABEL,
                'constraints' => [
                    new Type([
                        'type' => 'string',
                        'message' => ProductErrors::INVALID_VALUE
                    ])
                ]
            ])
            ->add('defaultAmount', NumberType::class, [
                'label' => ProductConfig::DEFAULT_AMOUNT_LABEL,
                'constraints' => [
                    new Positive([
                        'message' => ProductErrors::INVALID_AMOUNT
                    ])
                ]
            ])
            ->add('productsGroups', EntityType::class, [
                'label' => ProductConfig::PRODUCTS_GROUPS_LABEL,
                'multiple' => true,
                'choices' => $this->productsGroups,
                'class' => ProductsGroup::class,
                'invalid_message' => ProductErrors::INVALID_PRODUCTS_GROUP,
                'constraints' => [
                    new Count([
                        'min' => 1,
                        'minMessage' => ProductErrors::PRODUCTS_GROUP_MISSING
                    ]),
                    new SameUnits()
                ]
            ])
            ->add('unit', EntityType::class, [
                'label' => ProductConfig::UNIT_LABEL,
                'choices' => $this->units,
                'class' => Unit::class,
                'invalid_message' => ProductErrors::INVALID_UNIT,
                'constraints' => [
                    new NotBlank([
                        'message' => ProductErrors::UNIT_MISSING
                    ]),
                    new Callback(function($value, ExecutionContext $context) {
                        $validator = new ProductConnectedUnit($context);
                        $validator->validate($value);
                    })
                ]
            ])
            ->add('brand', EntityType::class, [
                'label' => ProductConfig::BRAND_LABEL,
                'choices' => $this->brands,
                'class' => Brand::class,
                'invalid_message' => ProductErrors::INVALID_BRAND,
            ])
            ->add('barcode', MaterialBarcode::class, [
                'label' => ProductConfig::BARCODE_LABEL,
                'attr' => [
                    'maxlength' => ProductConfig::NAME_BARCODE_LENGTH
                ],
                'constraints' => [
                    new Type([
                        'type' => 'string',
                        'message' => ProductErrors::INVALID_VALUE
                    ]),
                    new Length([
                        'max' => ProductConfig::NAME_BARCODE_LENGTH,
                        'maxMessage' => ProductErrors::BARCODE_TOO_LONG
                    ]),
                    new UniqueForUser(
                        $this->user,
                        ProductErrors::BARCODE_IN_USE,
                        $this->productRepository
                    )
                ]
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Product::class
        ]);
    }
}
