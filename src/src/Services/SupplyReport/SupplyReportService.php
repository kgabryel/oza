<?php

namespace App\Services\SupplyReport;

use App\Model\SupplyReport\Supplies;
use DateTime;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\StreamedResponse;

class SupplyReportService
{
    private string $date;

    public function __construct()
    {
        $this->date = (new DateTime())->format('d-m-Y');
    }

    public function create(array $supplies): Spreadsheet
    {
        $spreadsheet = new Spreadsheet();
        $sheet = WorksheetEditor::getCleanWorksheet($spreadsheet);
        if (empty($supplies)) {
            return $spreadsheet;
        }
        $parsedData = new Supplies($supplies);
        $generalPartEditor = new GeneralPartEditor($sheet, $parsedData);
        $generalPartEditor->set();
        $detailsPartEditor = new DetailsPartEditor($sheet, $parsedData);
        $detailsPartEditor->set();
        foreach (range('A', 'I') as $col) {
            $sheet->getColumnDimension($col)->setAutoSize(true);
        }

        return $spreadsheet;
    }

    public function download(Spreadsheet $spreadsheet): Response
    {
        $writer = new Xlsx($spreadsheet);
        $fileName = sprintf('Raport_%s.xlsx', $this->date);
        $response = new StreamedResponse(
            function () use ($writer) {
                $writer->save('php://output');
            }
        );
        $response->headers->set('Content-Type', 'application/vnd.ms-excel');
        $response->headers->set('Content-Disposition', sprintf('attachment;filename="%s"', $fileName));
        $response->headers->set('Cache-Control', 'max-age=0');

        return $response;
    }
}
