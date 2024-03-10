<?php

namespace App\Validator\CorrectUnit;

use App\Config\Message\Error\ShoppingListErrors;
use App\Model\Form\ShoppingListPosition;
use App\Model\Form\ShoppingPosition;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;

class CorrectUnitValidator extends ConstraintValidator
{
    /**
     * @param  ShoppingListPosition|ShoppingPosition  $value
     * @param  Constraint  $constraint
     *
     * @return void
     */
    public function validate(mixed $value, Constraint $constraint): void
    {
        if ($value->getProduct() === null && $value->getProductsGroup() === null) {
            return;
        }
        if ($value->getUnit() === null) {
            $this->context->buildViolation(ShoppingListErrors::UNIT_MISSING)->addViolation();
        }
    }
}
