<?php

namespace App\Validator;

use Symfony\Component\Form\Form;
use Symfony\Component\Validator\Context\ExecutionContext;

class GreaterThanOrEqual
{
    private ExecutionContext $context;
    private string $field;
    private string $message;

    public function __construct(ExecutionContext $context, string $field, string $message)
    {
        $this->context = $context;
        $this->field = $field;
        $this->message = $message;
    }

    public function validate(?float $value): void
    {
        if ($value === null) {
            return;
        }
        /** @var Form $form */
        $form = $this->context->getObject()->getParent();
        $connectedField = $form->get($this->field);
        if (!$connectedField->isValid()) {
            return;
        }

        if (((float)$connectedField->getViewData()) >= $value) {
            return;
        }
        $this->context->buildViolation($this->message)->addViolation();
    }
}
