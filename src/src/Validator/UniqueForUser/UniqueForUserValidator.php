<?php

namespace App\Validator\UniqueForUser;

use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;

class UniqueForUserValidator extends ConstraintValidator
{
    public function validate($value, Constraint $constraint): void
    {
        /* @var UniqueForUser $constraint */
        if ($value === null) {
            return;
        }
        $repository = $constraint->getRepository();
        $entity = $repository->filterForUser(
            $constraint->getColumnName(),
            $value,
            'user',
            $constraint->getUser(),
            $constraint->getExpect()
        );
        if ($entity === []) {
            return;
        }
        $this->context->buildViolation($constraint->getMessage())->setParameter('{{ value }}', $value)->addViolation();
    }
}
