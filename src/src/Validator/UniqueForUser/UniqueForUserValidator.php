<?php

namespace App\Validator\UniqueForUser;

use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;

class UniqueForUserValidator extends ConstraintValidator
{
    /**
     * @param  ?string  $value
     * @param  UniqueForUser  $constraint
     *
     * @return void
     */
    public function validate(mixed $value, Constraint $constraint): void
    {
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
