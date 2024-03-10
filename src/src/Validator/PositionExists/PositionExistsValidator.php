<?php

namespace App\Validator\PositionExists;

use App\Config\Message\Error\ShoppingListErrors;
use App\Model\Form\ShoppingListPosition;
use App\Model\Form\ShoppingPosition;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;

class PositionExistsValidator extends ConstraintValidator
{
    /**
     * @param  ShoppingListPosition|ShoppingPosition  $value
     * @param  Constraint  $constraint
     *
     * @return void
     */
    public function validate(mixed $value, Constraint $constraint): void
    {
        /* @var $value ShoppingListPosition */
        if ($value->getProductsGroup() !== null || $value->getProduct() !== null) {
            return;
        }
        $this->context->buildViolation(ShoppingListErrors::INVALID_PRODUCT)->addViolation();
    }
}
