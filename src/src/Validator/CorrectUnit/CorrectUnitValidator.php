<?php

namespace App\Validator\CorrectUnit;

use App\Config\Message\Error\ShoppingListErrors;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;

class CorrectUnitValidator extends ConstraintValidator
{
    public function validate($value, Constraint $constraint): void
    {
        if ($value->getProduct() === null && $value->getProductsGroup() === null) {
            return;
        }
        if ($value->getUnit() === null) {
            $this->context->buildViolation(ShoppingListErrors::UNIT_MISSING)->addViolation();
        }
    }
}
