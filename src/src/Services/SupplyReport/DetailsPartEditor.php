<?php

namespace App\Services\SupplyReport;

class DetailsPartEditor extends WorksheetEditor
{
    public function set(): void
    {
        foreach ($this->supplies->getSupplies() as $supply) {
            $this->addSupplyDetailHeader([7, 8]);
            $this->addSupplyPart($supply, [7, 8]);
            $description = $supply->getDescription();
            if ($description !== '') {
                $this->addDescriptionPart($description);
            }
            $parts = $supply->getParts();
            if (!empty($parts)) {
                $this->addDetailsHeader(self::DEFAULT_COLOR, [6, 7, 8, 9]);
                foreach ($parts as $part) {
                    $this->addSupplyPartPart($part, [6, 7, 8, 9], false);
                    $description = $part->getDescription();
                    if ($description !== '') {
                        $this->addDescriptionPart($description);
                    }
                }
            }
            $this->i++;
        }
    }
}
