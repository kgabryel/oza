<?php

namespace App\Services\SupplyReport;

use App\Model\SupplyReport\Supplies;
use App\Model\SupplyReport\Supply;
use App\Model\SupplyReport\SupplyPart;
use App\Utils\SupplyReportUtils;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

abstract class WorksheetEditor
{
    protected const AFTER_EXPIRY_DATE_COLOR = 'd50000';
    protected const BEFORE_EXPIRY_DATE_1_COLOR = 'FB8C00';
    protected const BEFORE_EXPIRY_DATE_2_COLOR = '4CAF50';
    protected const BORDER_COLOR = '000000';
    protected const DEFAULT_COLOR = '8b8b8b';
    protected int $i;
    protected Supplies $supplies;
    protected Worksheet $worksheet;

    public function __construct(Worksheet $worksheet, Supplies $supplies)
    {
        $this->worksheet = $worksheet;
        $this->supplies = $supplies;
        $this->i = 1;
    }

    public static function getCleanWorksheet(Spreadsheet $spreadsheet): Worksheet
    {
        $sheet = $spreadsheet->getActiveSheet();
        $sheet->setTitle('Raport');

        return $sheet;
    }

    abstract public function set(): void;

    protected function addDescriptionPart(string $description): void
    {
        $style = [];
        SupplyReportUtils::addCenterText($style);
        $this->worksheet->mergeCellsByColumnAndRow(6, $this->i, 9, $this->i);
        $this->worksheet->getCellByColumnAndRow(6, $this->i)
            ->setValue($description)
            ->getStyle()
            ->applyFromArray($style);
        $this->i++;
    }

    protected function addDetailsHeader(string $color, array $columns): void
    {
        $style = [];
        SupplyReportUtils::addBackground($style, $color);
        SupplyReportUtils::addCenterText($style);
        SupplyReportUtils::addBorder($style, self::BORDER_COLOR);
        $this->worksheet->getCellByColumnAndRow($columns[0], $this->i)
            ->setValue('Ilość')
            ->getStyle()
            ->applyFromArray($style);
        $this->worksheet->getCellByColumnAndRow($columns[1], $this->i)
            ->setValue('Produkt')
            ->getStyle()
            ->applyFromArray($style);
        $this->worksheet->getCellByColumnAndRow($columns[2], $this->i)
            ->setValue('Data ważności')
            ->getStyle()
            ->applyFromArray($style);
        $this->worksheet->getCellByColumnAndRow($columns[3], $this->i)
            ->setValue('Otwarte')
            ->getStyle()
            ->applyFromArray($style);
        $this->i++;
    }

    protected function addHeader(string $text, string $color, int $start, int $stop): void
    {
        $style = [];
        SupplyReportUtils::addBackground($style, $color);
        SupplyReportUtils::addCenterText($style);
        SupplyReportUtils::addBorder($style, self::BORDER_COLOR);
        $this->worksheet->mergeCellsByColumnAndRow($start, $this->i, $stop, $this->i);
        $this->worksheet->getCellByColumnAndRow($start, $this->i)->setValue($text)->getStyle()->applyFromArray($style);
        $this->worksheet->getCellByColumnAndRow($stop, $this->i)->getStyle()->applyFromArray($style);
        $this->i++;
    }

    protected function addSupplyDetailHeader(array $columns): void
    {
        $style = [];
        SupplyReportUtils::addBackground($style, self::DEFAULT_COLOR);
        SupplyReportUtils::addCenterText($style);
        SupplyReportUtils::addBorder($style, self::BORDER_COLOR);
        $this->worksheet->getCellByColumnAndRow($columns[0], $this->i)
            ->setValue('Ilość')
            ->getStyle()
            ->applyFromArray($style);
        $this->worksheet->getCellByColumnAndRow($columns[1], $this->i)
            ->setValue('Produkt')
            ->getStyle()
            ->applyFromArray($style);
        $this->i++;
    }

    protected function addSupplyPart(Supply $supply, array $columns): void
    {
        $this->worksheet->getCellByColumnAndRow($columns[0], $this->i)
            ->setValue($supply->getAmount());
        $this->worksheet->getCellByColumnAndRow($columns[1], $this->i)
            ->setValue($supply->getProduct());
        $this->i++;
    }

    protected function addSupplyPartPart(SupplyPart $supplyPart, array $columns, bool $withGroup): void
    {
        $this->worksheet->getCellByColumnAndRow($columns[0], $this->i)
            ->setValue($supplyPart->getAmount());
        $this->worksheet->getCellByColumnAndRow($columns[1], $this->i)
            ->setValue($supplyPart->getProduct($withGroup));
        $this->worksheet->getCellByColumnAndRow($columns[2], $this->i)
            ->setValue($supplyPart->getDate());
        $this->worksheet->getCellByColumnAndRow($columns[3], $this->i)
            ->setValue($supplyPart->isOpen());
        $this->i++;
    }
}
