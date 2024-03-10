<?php

namespace App\Validator\CorrectPassword;

use App\Config\Message\Error\ResetPasswordErrors;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;

class CorrectPasswordValidator extends ConstraintValidator
{
    /**
     * @param  mixed  $value
     * @param  CorrectPassword  $constraint
     *
     * @return void
     */
    public function validate(mixed $value, Constraint $constraint): void
    {
        if ($constraint->getUserPasswordHasher()->isPasswordValid($constraint->getUser(), (string)($value ?? ''))) {
            return;
        }
        $this->context->buildViolation(ResetPasswordErrors::INVALID_PASSWORD)->addViolation();
    }
}
