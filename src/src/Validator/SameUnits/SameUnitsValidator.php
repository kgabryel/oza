<?php

namespace App\Validator\SameUnits;

use App\Config\Message\Error\ProductsGroupErrors;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;

class SameUnitsValidator extends ConstraintValidator
{
    /**
     * @param  array  $value
     * @param  SameUnits  $constraint
     *
     * @return void
     */
    public function validate(mixed $value, Constraint $constraint): void
    {
        $unit = null;
        $valid = true;
        foreach ($value as $productsGroup) {
            if ($unit === null) {
                $unit = $productsGroup->getUnit()->getId();
                continue;
            }
            if ($productsGroup->getUnit()->getId() !== $unit) {
                $valid = false;
                break;
            }
        }
        if ($valid) {
            return;
        }
        $this->context->buildViolation(ProductsGroupErrors::DIFFERENT_UNITS)->addViolation();
    }
}
