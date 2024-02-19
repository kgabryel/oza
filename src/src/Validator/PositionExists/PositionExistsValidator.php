<?php

namespace App\Validator\PositionExists;

use App\Config\Message\Error\ShoppingListErrors;
use App\Model\Form\ShoppingListPosition;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;

class PositionExistsValidator extends ConstraintValidator
{
    public function validate($value, Constraint $constraint): void
    {
        /* @var $value ShoppingListPosition */
        if ($value->getProductsGroup() === null && $value->getProduct() === null) {
            return;
        }
        if ($value->getProductsGroup() !== null || $value->getProduct() !== null) {
            return;
        }
        if ($value->getProduct() !== null) {
            $this->context->buildViolation(ShoppingListErrors::INVALID_PRODUCT)->addViolation();
        } else {
            $this->context->buildViolation(ShoppingListErrors::INVALID_PRODUCTS_GROUP)->addViolation();
        }
    }
}
