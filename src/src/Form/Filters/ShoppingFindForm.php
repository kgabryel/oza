<?php

namespace App\Form\Filters;

use App\Config\Form\ShoppingConfig;
use App\Config\Message\Error\ShoppingErrors;
use App\Entity\Product;
use App\Entity\ProductsGroup;
use App\Entity\Shop;
use App\Entity\Unit;
use App\Field\MaterialDate;
use App\Form\UserForm;
use App\Model\Filter\Shopping;
use App\Repository\ProductRepository;
use App\Repository\ProductsGroupRepository;
use App\Repository\ShopRepository;
use App\Repository\UnitRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

class ShoppingFindForm extends UserForm
{
    private array $products;
    private array $productsGroups;
    private array $shops;
    private array $units;

    public function __construct(
        UnitRepository $repository,
        ShopRepository $shopRepository,
        ProductsGroupRepository $productsGroupRepository,
        ProductRepository $productRepository,
        TokenStorageInterface $tokenStorage
    ) {
        parent::__construct($tokenStorage);
        $this->units = $repository->findForUser($this->user);
        $this->shops = $shopRepository->findForUser($this->user);
        $this->products = $productRepository->findForUser($this->user);
        $this->productsGroups = $productsGroupRepository->findForUser($this->user);
    }

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder->add('dateFrom', MaterialDate::class, [
            'label' => ShoppingConfig::DATE_FROM_LABEL
        ])
            ->add('dateTo', MaterialDate::class, [
                'label' => ShoppingConfig::DATE_TO_LABEL
            ])
            ->add('shops', EntityType::class, [
                'label' => ShoppingConfig::SHOP_LABEL,
                'multiple' => true,
                'choices' => $this->shops,
                'class' => Shop::class,
                'invalid_message' => ShoppingErrors::INVALID_VALUE
            ])
            ->add('units', EntityType::class, [
                'label' => ShoppingConfig::UNIT_LABEL,
                'multiple' => true,
                'choices' => $this->units,
                'class' => Unit::class,
                'invalid_message' => ShoppingErrors::INVALID_VALUE
            ])
            ->add('products', EntityType::class, [
                'label' => ShoppingConfig::PRODUCT_LABEL,
                'attr' => [
                    'class' => 'Product'
                ],
                'multiple' => true,
                'choices' => $this->products,
                'class' => Product::class,
                'invalid_message' => ShoppingErrors::INVALID_VALUE
            ])
            ->add('productsGroups', EntityType::class, [
                'label' => ShoppingConfig::PRODUCTS_GROUP_LABEL,
                'multiple' => true,
                'choices' => $this->productsGroups,
                'class' => ProductsGroup::class,
                'invalid_message' => ShoppingErrors::INVALID_VALUE
            ])
            ->setMethod('GET');
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Shopping::class,
            'csrf_protection' => false
        ]);
    }

    public function getBlockPrefix(): string
    {
        return '';
    }
}
