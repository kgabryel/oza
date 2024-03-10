<?php

namespace App\Form;

use App\Config\Form\ProductConfig;
use App\Config\Form\ProductsGroupConfig;
use App\Config\Message\Error\ProductErrors;
use App\Entity\Brand;
use App\Entity\Product;
use App\Entity\ProductsGroup;
use App\Entity\Unit;
use App\Field\MaterialBarcode;
use App\Field\Wysiwyg;
use App\Model\Form\EditProduct;
use App\Repository\BrandRepository;
use App\Repository\ProductRepository;
use App\Services\UserService;
use App\Utils\UnitUtils;
use App\Validator\UniqueForUser\UniqueForUser;
use App\Validator\UniqueProductName;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Callback;
use Symfony\Component\Validator\Constraints\Count;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Type;
use Symfony\Component\Validator\Context\ExecutionContext;

class EditProductForm extends UserForm
{
    private array $brands;
    private ProductRepository $productRepository;

    public function __construct(
        ProductRepository $productRepository,
        UserService $userService,
        BrandRepository $brandRepository
    ) {
        parent::__construct($userService);
        $this->productRepository = $productRepository;
        $this->brands = $brandRepository->findForUser($this->user);
    }

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        /** @var Product $product */
        $product = $this->productRepository->find($options['id']);
        $unit = $product->getUnit();
        $unit = $unit->getMain() ?? $unit;
        $units = UnitUtils::getUnitList($unit);
        $builder->add('name', TextType::class, [
            'label' => ProductsGroupConfig::NAME_LABEL,
            'attr' => [
                'maxlength' => ProductsGroupConfig::NAME_MAX_LENGTH
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
                new Callback(function($value, ExecutionContext $context) use ($options) {
                    $validator = new UniqueProductName(
                        $context,
                        ProductErrors::NAME_WITH_BRAND_IN_USE,
                        ProductErrors::NAME_WITHOUT_BRAND_IN_USE,
                        $this->productRepository,
                        $this->user,
                        $options['id']
                    );
                    $validator->validate($value);
                })
            ]
        ])
            ->add('note', Wysiwyg::class, [
                'label' => ProductsGroupConfig::DESCRIPTION_LABEL,
                'constraints' => [
                    new Type([
                        'type' => 'string',
                        'message' => ProductErrors::INVALID_VALUE
                    ])
                ]
            ])
            ->add('productsGroups', EntityType::class, [
                'label' => ProductConfig::PRODUCTS_GROUPS_LABEL,
                'multiple' => true,
                'choices' => $unit->getGroups(),
                'class' => ProductsGroup::class,
                'invalid_message' => ProductErrors::INVALID_PRODUCTS_GROUP,
                'constraints' => [
                    new Count([
                        'min' => 1,
                        'minMessage' => ProductErrors::PRODUCTS_GROUP_MISSING
                    ])
                ]
            ])
            ->add('unit', EntityType::class, [
                'label' => ProductConfig::UNIT_LABEL,
                'choices' => $units,
                'class' => Unit::class,
                'invalid_message' => ProductErrors::INVALID_UNIT,
                'constraints' => [
                    new NotBlank([
                        'message' => ProductErrors::UNIT_MISSING
                    ]),
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
                        $this->productRepository,
                        expect: $options['id']
                    )
                ]
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => EditProduct::class,
            'id' => 0
        ]);
    }
}
