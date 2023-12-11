<?php

namespace App\Validator;

use App\Config\Message\Error\ShoppingErrors;
use App\Entity\Product;
use App\Entity\ProductsGroup;
use App\Entity\Supply;
use App\Services\PositionFactory\PositionFactory;
use Symfony\Component\Form\Form;
use Symfony\Component\Validator\Context\ExecutionContext;

class ValidShoppingSupply
{
    private ExecutionContext $context;
    private PositionFactory $factory;

    public function __construct(ExecutionContext $context, PositionFactory $factory)
    {
        $this->context = $context;
        $this->factory = $factory;
    }

    public function validate(?Supply $value): void
    {
        if ($value === null) {
            return;
        }
        /** @var Form $form */
        $form = $this->context->getObject()->getParent();
        $createSupply = $form->get('createSupply')->getNormData();
        if (!$createSupply) {
            return;
        }
        $concreteFactory = $this->factory->get(
            $form->get('type')->getNormData(),
            (int)$form->get('position')->getNormData()
        );
        $productsGroup = $concreteFactory->getProductsGroup();
        $product = $concreteFactory->getProduct();
        if ($productsGroup === null && $product === null) {
            return;
        }
        if ($productsGroup !== null) {
            if ($this->checkProductsGroup($productsGroup, $value)) {
                return;
            }
        } elseif ($this->checkProduct($product, $value)) {
            return;
        }
        $this->context->buildViolation(ShoppingErrors::INVALID_SUPPLY)->addViolation();
    }

    private function checkProductsGroup(ProductsGroup $productsGroup, Supply $supply): bool
    {
        $productsGroupSupply = $productsGroup->getSupply();
        if ($productsGroupSupply === null) {
            return false;
        }
        return $productsGroupSupply->getId() === $supply->getId();
    }

    private function checkProduct(Product $product, Supply $supply): bool
    {
        foreach ($product->getGroups() as $group) {
            if ($this->checkProductsGroup($group, $supply)) {
                return true;
            }
        }

        return false;
    }
    /*
    private function showError(?Brand $brand, ?string $value): void
    {
        if ($brand === null) {
            $this->context->buildViolation($this->messageWithoutBrand, [
                '{{ name }}' => $value
            ])
                ->addViolation();
        } else {
            $this->context->buildViolation($this->messageWithBrand, [
                '{{ name }}' => $value,
                '{{ brand }}' => $brand->getName()
            ])
                ->addViolation();
        }
    }
    */
}
