<?php

namespace App\Validator;

use App\Config\Message\Error\ResetPasswordErrors;
use Symfony\Component\Form\Form;
use Symfony\Component\Validator\Context\ExecutionContext;

class DifferentPassword
{
    private ExecutionContext $context;

    public function __construct(ExecutionContext $context)
    {
        $this->context = $context;
    }

    public function validate(?string $value): void
    {
        if ($value === null) {
            return;
        }
        /** @var Form $form */
        $form = $this->context->getObject()->getParent();
        $oldPassword = $form->get('oldPassword');
        if (!$oldPassword->isValid()) {
            return;
        }
        if ($value !== $oldPassword->getData()) {
            return;
        }
        $this->context->buildViolation(ResetPasswordErrors::SAME_PASSWORD)->addViolation();
    }
}
