<?php

namespace App\Validator\CorrectPassword;

use App\Config\Message\Error\ResetPasswordErrors;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;

class CorrectPasswordValidator extends ConstraintValidator
{
    public function validate($value, Constraint $constraint): void
    {
        /* @var $constraint CorrectPassword */
        if ($constraint->getUserPasswordHasher()->isPasswordValid($constraint->getUser(), $value ?? '')) {
            return;
        }
        $this->context->buildViolation(ResetPasswordErrors::INVALID_PASSWORD)->addViolation();
    }
}
