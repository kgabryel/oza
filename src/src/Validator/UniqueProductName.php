<?php

namespace App\Validator;

use App\Entity\Brand;
use App\Entity\User;
use App\Repository\ProductRepository;
use App\Utils\FormUtils;
use Symfony\Component\Validator\Context\ExecutionContext;

class UniqueProductName
{
    private ExecutionContext $context;
    private string $messageWithBrand;
    private string $messageWithoutBrand;

    private int $productId;
    private ProductRepository $productRepository;
    private User $user;

    public function __construct(
        ExecutionContext $context,
        string $messageWithBrand,
        string $messageWithoutBrand,
        ProductRepository $productRepository,
        User $user,
        int $productId = 0
    ) {
        $this->context = $context;
        $this->messageWithBrand = $messageWithBrand;
        $this->messageWithoutBrand = $messageWithoutBrand;
        $this->productRepository = $productRepository;
        $this->user = $user;
        $this->productId = $productId;
    }

    public function validate(?string $value): void
    {
        if ($value === null) {
            return;
        }
        $form = FormUtils::getParentForm($this->context);
        $connectedField = $form->get('brand');
        if (!$connectedField->isValid()) {
            return;
        }

        /** @var Brand $brand */
        $brand = $connectedField->getNormData();
        $products = $this->productRepository->findBy([
            'user' => $this->user,
            'name' => $value,
            'brand' => $brand
        ]);
        if (count($products) > 1) {
            $this->showError($brand, $value);

            return;
        }
        $product = $products[0] ?? null;
        if ($product === null) {
            return;
        }
        if ($product->getId() === $this->productId) {
            return;
        }
        $this->showError($brand, $value);
    }

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
}
