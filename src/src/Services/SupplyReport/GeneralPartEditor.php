<?php

namespace App\Services\SupplyReport;

class GeneralPartEditor extends WorksheetEditor
{
    public function set(): void
    {
        $suppliesAfterExpiryDate = $this->supplies->getAfterExpiryDate();
        $suppliesBeforeExpiryDate1 = $this->supplies->getBeforeExpiryDate1();
        $suppliesBeforeExpiryDate2 = $this->supplies->getBeforeExpiryDate2();
        if (!empty($suppliesAfterExpiryDate)) {
            $this->addSupplies('Produkty po terminie', $suppliesAfterExpiryDate, self::AFTER_EXPIRY_DATE_COLOR);
        }

        if (!empty($suppliesBeforeExpiryDate1)) {
            $this->addSupplies(
                'Produkty z krótką datą ważności (do tygodnia)',
                $suppliesBeforeExpiryDate1,
                self::BEFORE_EXPIRY_DATE_1_COLOR
            );
        }

        if (!empty($suppliesBeforeExpiryDate2)) {
            $this->addSupplies(
                'Produkty z krótką datą ważności (do miesiąca)',
                $suppliesBeforeExpiryDate2,
                self::BEFORE_EXPIRY_DATE_2_COLOR
            );
        }
        $this->addHeader('Zapasy', self::DEFAULT_COLOR, 2, 3);
        $this->addSupplyDetailHeader([2, 3]);
        foreach ($this->supplies->getSupplies() as $supply) {
            $this->addSupplyPart($supply, [2, 3]);
        }
    }

    private function addSupplies(string $headerText, array $supplies, string $color): void
    {
        $this->addSupplyHeader($headerText);
        $this->addDetailsHeader($color, [1, 2, 3, 4]);
        foreach ($supplies as $supplyPart) {
            $this->addSupplyPartPart($supplyPart, [1, 2, 3, 4], true);
        }
        $this->i++;
    }

    private function addSupplyHeader(string $text): void
    {
        $this->addHeader($text, self::BEFORE_EXPIRY_DATE_2_COLOR, 1, 4);
    }
}
