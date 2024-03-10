<?php

namespace App\Form\Filters;

use App\Config\Form\ProductConfig;
use App\Config\Message\Error\ProductErrors;
use App\Entity\Brand;
use App\Entity\ProductsGroup;
use App\Entity\Unit;
use App\Field\MaterialBarcode;
use App\Form\UserForm;
use App\Model\Filter\Product;
use App\Repository\BrandRepository;
use App\Repository\ProductsGroupRepository;
use App\Repository\UnitRepository;
use App\Services\UserService;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ProductFindForm extends UserForm
{
    private array $brands;
    private array $productsGroups;
    private array $units;

    public function __construct(
        UnitRepository $repository,
        ProductsGroupRepository $productsGroupRepository,
        UserService $userService,
        BrandRepository $brandRepository
    ) {
        parent::__construct($userService);
        $this->units = $repository->findBy([
            'user' => $this->user
        ]);
        $this->productsGroups = $productsGroupRepository->findBy([
            'user' => $this->user
        ]);
        $this->brands = $brandRepository->findBy([
            'user' => $this->user
        ]);
    }

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder->add('name', TextType::class, [
            'label' => ProductConfig::NAME_LABEL
        ])
            ->add('productsGroups', EntityType::class, [
                'label' => ProductConfig::PRODUCTS_GROUPS_LABEL,
                'multiple' => true,
                'choices' => $this->productsGroups,
                'class' => ProductsGroup::class,
                'invalid_message' => ProductErrors::INVALID_VALUE
            ])
            ->add('units', EntityType::class, [
                'label' => ProductConfig::UNIT_LABEL,
                'multiple' => true,
                'choices' => $this->units,
                'class' => Unit::class,
                'invalid_message' => ProductErrors::INVALID_VALUE
            ])
            ->add('productsGroupUnits', EntityType::class, [
                'label' => ProductConfig::PRODUCTS_GROUPS_UNITS_LABEL,
                'multiple' => true,
                'choices' => $this->units,
                'class' => Unit::class,
                'invalid_message' => ProductErrors::INVALID_VALUE
            ])
            ->add('brands', EntityType::class, [
                'label' => ProductConfig::BRAND_LABEL,
                'multiple' => true,
                'choices' => $this->brands,
                'class' => Brand::class,
                'invalid_message' => ProductErrors::INVALID_VALUE
            ])
            ->add('barcode', MaterialBarcode::class, [
                'label' => ProductConfig::BARCODE_LABEL
            ])
            ->setMethod('GET');
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Product::class,
            'csrf_protection' => false
        ]);
    }

    public function getBlockPrefix(): string
    {
        return '';
    }
}
