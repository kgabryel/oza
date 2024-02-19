<?php

namespace App\Utils;

use App\Model\SupplyReport\SupplyPart;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Fill;

class SupplyReportUtils
{
    public static function addBackground(array &$style, string $color): void
    {
        $style['fill'] = [
            'fillType' => Fill::FILL_SOLID,
            'color' => [
                'rgb' => $color
            ]
        ];
    }

    public static function addBorder(array &$style, string $color): void
    {
        $style['borders'] = [
            'outline' => [
                'borderStyle' => Border::BORDER_THIN,
                'color' => [
                    'rgb' => $color
                ]
            ]
        ];
    }

    public static function addCenterText(array &$style): void
    {
        $style['alignment'] = [
            'horizontal' => Alignment::HORIZONTAL_CENTER,
        ];
    }

    public static function sort(array $suppliesParts): void
    {
        usort($suppliesParts, static function (SupplyPart $a, SupplyPart $b): int {
            $aDate = $a->getDateOfConsumption();
            $bDate = $b->getDateOfConsumption();
            if ($aDate === null && $bDate === null) {
                return 0;
            }
            if ($bDate === null || $aDate < $bDate) {
                return -1;
            }
            if ($aDate === null || $aDate > $bDate) {
                return 1;
            }

            return 0;
        });
    }
}
