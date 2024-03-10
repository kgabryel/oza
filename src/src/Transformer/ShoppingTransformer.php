<?php

namespace App\Transformer;

use App\Config\ProductPosition;
use App\Entity\Product;
use App\Entity\ProductsGroup;
use App\Model\Form\ShoppingPosition;
use App\Services\PositionFactory\PositionFactory;
use LogicException;
use Symfony\Component\Form\DataTransformerInterface;

class ShoppingTransformer implements DataTransformerInterface
{
    private PositionFactory $factory;

    public function __construct(PositionFactory $factory)
    {
        $this->factory = $factory;
    }

    public function reverseTransform(mixed $value): ShoppingPosition
    {
        return new ShoppingPosition($value, $this->factory);
    }

    public function transform(mixed $value): array
    {
        if (null === $value) {
            return [
                'position' => 0,
                'unit' => null,
                'amount' => 0,
                'type' => ProductPosition::PRODUCTS_GROUP,
                'createSupply' => false,
                'supply' => null
            ];
        }
        if (!$value instanceof ShoppingPosition) {
            throw new LogicException(
                sprintf(
                    'The %s can only be used with %s objects',
                    __CLASS__,
                    ShoppingPosition::class
                )
            );
        }
        /** @var ProductsGroup|Product $position */
        $position = $value->getProduct() ?? $value->getProductsGroup();

        return [
            'position' => $position->getId(),
            'unit' => $value->getUnit(),
            'amount' => $value->getAmount(),
            'type' => $value->getType(),
            'createSupply' => $value->createSupply(),
            'supply' => $value->getSupply(),
        ];
    }
}
