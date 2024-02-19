<?php

namespace App\Model\SupplyReport;

use App\Entity\Supply as Entity;
use App\Services\SupplyReport\DateStrategy\AfterExpiryDateStrategy;
use App\Services\SupplyReport\DateStrategy\BeforeExpiryDateStrategy;
use App\Services\SupplyReport\DateStrategy\WithoutExpiryDateStrategy;
use App\Utils\SupplyReportUtils;
use DateTime;

class Supplies
{
    private array $afterExpiryDate;
    private array $beforeExpiryDate1;
    private array $beforeExpiryDate2;
    /** @var Supply[] */
    private array $supplies;

    public function __construct(array $supplies)
    {
        $this->beforeExpiryDate1 = [];
        $this->beforeExpiryDate2 = [];
        $this->afterExpiryDate = [];
        $this->supplies = [];
        $now = new DateTime();
        /** @var Entity $supply */
        foreach ($supplies as $supply) {
            $id = $supply->getId();
            $this->supplies[$id] = new Supply($supply);
            foreach ($supply->getSupplyParts() as $part) {
                $date = $part->getDateOfConsumption();
                $this->supplies[$id]->addPart(new SupplyPart($part, new WithoutExpiryDateStrategy($date)));
                if ($date === null) {
                    continue;
                }
                $interval = $date->diff($now)->days;
                if ($now >= $date && $interval > 0) {
                    $this->afterExpiryDate[] = new SupplyPart($part, new AfterExpiryDateStrategy($date));
                } elseif ($interval < 8) {
                    $this->beforeExpiryDate1[] = new SupplyPart($part, new BeforeExpiryDateStrategy($date));
                } elseif ($interval < 31) {
                    $this->beforeExpiryDate2[] = new SupplyPart($part, new BeforeExpiryDateStrategy($date));
                }
            }
        }
    }

    public function getAfterExpiryDate(): array
    {
        $suppliesParts = $this->afterExpiryDate;
        SupplyReportUtils::sort($suppliesParts);

        return $suppliesParts;
    }

    public function getBeforeExpiryDate1(): array
    {
        $suppliesParts = $this->beforeExpiryDate1;
        SupplyReportUtils::sort($suppliesParts);

        return $suppliesParts;
    }

    public function getBeforeExpiryDate2(): array
    {
        $suppliesParts = $this->beforeExpiryDate2;
        SupplyReportUtils::sort($suppliesParts);

        return $suppliesParts;
    }

    public function getSupplies(): array
    {
        $supplies = $this->supplies;
        usort($supplies, static fn(Supply $a, Supply $b): int => strcmp($a->getProduct(), $b->getProduct()));

        return $supplies;
    }
}
