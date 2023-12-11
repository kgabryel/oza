<?php

namespace App\Validator\ConnectedUnit;

use App\Entity\ProductsGroup;
use App\Entity\Unit;
use App\Utils\UnitUtils;
use Symfony\Component\Form\Form;
use Symfony\Component\Validator\Context\ExecutionContext;

abstract class ConnectedUnit
{
    protected Form $connectedField;
    protected ExecutionContext $context;
    protected ?ProductsGroup $productsGroup;

    public function __construct(ExecutionContext $context)
    {
        $this->context = $context;
        $this->connectedField = $this->getConnectedField();
        $this->productsGroup = $this->getProductGroup();
    }

    abstract protected function getConnectedField(): Form;

    abstract protected function getProductGroup(): ?ProductsGroup;

    public function validate(?Unit $unit): void
    {
        if ($unit === null || $this->productsGroup === null) {
            return;
        }
        if ($this->connectedField->getErrors()->count() !== 0) {
            return;
        }
        if (UnitUtils::checkUnit($this->productsGroup, null, $unit)) {
            return;
        }
        $this->context->buildViolation($this->getError())->addViolation();
    }

    abstract protected function getError(): string;
}
