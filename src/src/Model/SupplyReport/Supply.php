<?php

namespace App\Model\SupplyReport;

use App\Entity\Supply as Entity;
use App\Utils\SupplyReportUtils;

class Supply
{
    private array $parts;
    private Entity $supply;

    public function __construct(Entity $supply)
    {
        $this->supply = $supply;
        $this->parts = [];
    }

    public function addPart(SupplyPart $supplyPart): void
    {
        $this->parts[] = $supplyPart;
    }

    public function getAmount(): string
    {
        return sprintf('%s %s', $this->supply->getAmount(), $this->supply->getGroup()->getBaseUnit()->getShortcut());
    }

    public function getDescription(): string
    {
        return $this->supply->getDescription() ?? '';
    }

    public function getParts(): array
    {
        $parts = $this->parts;
        SupplyReportUtils::sort($parts);

        return $parts;
    }

    public function getProduct(): string
    {
        return $this->supply->getGroup()->getName();
    }
}
