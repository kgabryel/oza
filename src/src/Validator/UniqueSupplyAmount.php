<?php

namespace App\Validator;

use App\Config\Message\Error\SupplyAlertErrors;
use App\Entity\Unit;
use App\Repository\SupplyAlertRepository;
use App\Utils\UnitUtils;
use Symfony\Component\Validator\Context\ExecutionContext;

class UniqueSupplyAmount
{
    private ExecutionContext $context;
    private SupplyAlertRepository $supplyAlertRepository;
    private int $supplyId;
    private Unit $unit;

    public function __construct(ExecutionContext $context, int $supplyId, SupplyAlertRepository $supplyAlertRepository)
    {
        $this->context = $context;
        $this->supplyId = $supplyId;
        $this->supplyAlertRepository = $supplyAlertRepository;
    }

    public function validate(?float $value): void
    {
        if ($value === null || !$this->checkUnit()) {
            return;
        }
        $unitId = $this->unit->getId();
        $alerts = $this->supplyAlertRepository->findForSupply($this->supplyId);
        foreach ($alerts as $alert) {
            $alertUnit = $alert->getUnit();
            if (($alertUnit->getId() === $unitId) && $alert->getAmount() === $value) {
                $this->context->buildViolation(SupplyAlertErrors::AMOUNT_IN_USE)->addViolation();

                return;
            }
            $parsedAmount = UnitUtils::parseAmount($alert->getAmount(), $alertUnit, $this->unit);
            if (round($parsedAmount, 3) === $value) {
                $this->context->buildViolation(SupplyAlertErrors::AMOUNT_IN_USE)->addViolation();

                return;
            }
        }
    }

    private function checkUnit(): bool
    {
        $unitField = $this->context->getRoot()['unit'];
        if ($unitField->getErrors()->count() !== 0) {
            return false;
        }
        $this->unit = $unitField->getData();

        return true;
    }
}
