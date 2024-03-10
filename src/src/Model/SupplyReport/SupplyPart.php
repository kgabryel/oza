<?php

namespace App\Model\SupplyReport;

use App\Entity\SupplyPart as Entity;
use App\Services\SupplyReport\DateStrategy\DateStrategy;
use DateTimeInterface;

class SupplyPart
{
    private DateStrategy $dateStrategy;
    private Entity $supplyPart;

    public function __construct(Entity $supplyPart, DateStrategy $dateStrategy)
    {
        $this->supplyPart = $supplyPart;
        $this->dateStrategy = $dateStrategy;
    }

    public function getAmount(): string
    {
        $partAmount = $this->supplyPart->getPart();
        if ($partAmount === 1) {
            return sprintf('%s %s', $this->supplyPart->getAmount(), $this->supplyPart->getUnit()->getShortcut());
        }

        return sprintf(
            '%s x %s %s',
            $partAmount,
            $this->supplyPart->getAmount(),
            $this->supplyPart->getUnit()->getShortcut()
        );
    }

    public function getDate(): string
    {
        return $this->dateStrategy->get();
    }

    public function getDateOfConsumption(): ?DateTimeInterface
    {
        return $this->supplyPart->getDateOfConsumption();
    }

    public function getDescription(): string
    {
        return $this->supplyPart->getDescription();
    }

    public function getProduct(bool $withGroup = true): string
    {
        $product = $this->supplyPart->getProduct();
        if ($product !== null) {
            return (string)$product;
        }
        if (!$withGroup) {
            return '';
        }

        return $this->supplyPart->getSupply()->getGroup()->getName();
    }

    public function isOpen(): string
    {
        return $this->supplyPart->isOpen() ? 'Tak' : 'Nie';
    }
}
