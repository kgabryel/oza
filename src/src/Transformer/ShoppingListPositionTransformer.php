<?php

namespace App\Transformer;

use App\Config\ProductPosition;
use App\Model\Form\ShoppingListPosition;
use App\Services\PositionFactory\PositionFactory;
use LogicException;
use Symfony\Component\Form\DataTransformerInterface;

class ShoppingListPositionTransformer implements DataTransformerInterface
{
    private PositionFactory $factory;

    public function __construct(PositionFactory $factory)
    {
        $this->factory = $factory;
    }

    /**
     * @inheritDoc
     */
    public function reverseTransform($value)
    {
        return new ShoppingListPosition($value, $this->factory);
    }

    public function transform($value): array
    {
        if (null === $value) {
            return [
                'position' => 0,
                'unit' => null,
                'amount' => 0,
                'type' => ProductPosition::PRODUCTS_GROUP,
                'checked' => false,
                'shop' => null,
                'description' => null
            ];
        }
        if (!$value instanceof ShoppingListPosition) {
            throw new LogicException(
                sprintf(
                    'The %s can only be used with %s objects',
                    __CLASS__,
                    ShoppingListPosition::class
                )
            );
        }

        return [
            'position' => $value->getPosition(),
            'unit' => $value->getUnit(),
            'amount' => $value->getAmount(),
            'type' => $value->getType(),
            'checked' => $value->isChecked(),
            'shop' => $value->getShop(),
            'description' => $value->getDescription()
        ];
    }
}
