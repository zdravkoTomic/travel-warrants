<?php

namespace App\Controller\Payment;

use App\Entity\Payment;
use App\Service\Payment\PaymentService;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\ResponseHeaderBag;
use Symfony\Component\HttpFoundation\StreamedResponse;

class DownloadPaymentExpensesReportAction extends AbstractController
{
    public function __construct(readonly PaymentService $paymentService)
    {
    }

    public function __invoke(Payment $payment): JsonResponse|Response
    {
        $spreadsheet = new Spreadsheet();
        $sheet       = $spreadsheet->getActiveSheet();

        $expenseData = $this->paymentService->getPaymentExpenses($payment);

        $sheet->mergeCells("A1:C1");
        $sheet->setCellValue("A1", 'Identifikacijski broj: ' . $payment->getId());
        $sheet->getStyle('A1')->getFont()->setSize(14);

        $sheet->setCellValue("A3", 'Nalog');
        $sheet->setCellValue("B3", 'Djelatnik Ime');
        $sheet->setCellValue("C3", 'Djelatnik Prezime');
        $sheet->setCellValue("D3", 'Organizacijski dio');
        $sheet->setCellValue("E3", 'Vrsta troška');
        $sheet->setCellValue("F3", 'Vrsta troška naziv');
        $sheet->setCellValue("G3", 'Iznos');
        $sheet->setCellValue("H3", 'Valuta');
        $sheet->setCellValue("I3", 'Valuta naziv');

        $sheet->getStyle('A1')->getFont()->setBold(true);
        $sheet->getStyle('A3:I3')->getFont()->setBold(true);

        $dataRow = 4;

        $totals = [];

        foreach ($expenseData as $index => $expense) {
            $sheet->setCellValue("A{$dataRow}", $expense['warrant_code']);
            $sheet->setCellValue("B{$dataRow}", $expense['employee_name']);
            $sheet->setCellValue("C{$dataRow}", $expense['employee_surname']);
            $sheet->setCellValue("D{$dataRow}", $expense['department_name']);
            $sheet->setCellValue("E{$dataRow}", $expense['expense_type_code']);
            $sheet->setCellValue("F{$dataRow}", $expense['expense_type_name']);
            $sheet->setCellValue("G{$dataRow}", $expense['expense_amount']);
            $sheet->setCellValue("H{$dataRow}", $expense['currency_code']);
            $sheet->setCellValue("I{$dataRow}", $expense['currency_name']);

            if (!isset($totals[$expense['currency_code']])) {
                $totals[$expense['currency_code']] = 0;
            }
            $totals[$expense['currency_code']] += $expense['expense_amount'];

            if (
                $index === count($expenseData) - 1 ||
                $expenseData[$index + 1]['warrant_code'] !== $expense['warrant_code']
            ) {
                $dataRow++;

                $totalString = implode(
                    '; ',
                    array_map(
                        function ($currencyCode) use ($totals) {
                            return "{$totals[$currencyCode]} $currencyCode";
                        },
                        array_keys($totals)
                    )
                );

                $sheet->mergeCells("G{$dataRow}:I{$dataRow}");

                $sheet->setCellValue("G{$dataRow}", 'Ukupno: ' . $totalString);

                $sheet->getStyle("G{$dataRow}")->getAlignment()
                    ->setHorizontal(Alignment::HORIZONTAL_LEFT);

                $sheet->getStyle("G{$dataRow}")->getFont()->setBold(true);
                $totals = [];
            }

            $dataRow++;
        }

        foreach (range('A', 'I') as $col) {
            $sheet->getColumnDimension($col)->setAutoSize(true);
        }

        $writer = new Xlsx($spreadsheet);

        $response = new StreamedResponse(
            function () use ($writer) {
                $writer->save('php://output');
            }
        );
        $response->headers->set('Content-Type', 'application/vnd.ms-excel');
        $response->headers->set(
            'Content-Disposition',
            $response->headers->makeDisposition(
                ResponseHeaderBag::DISPOSITION_ATTACHMENT,
                'payment'. $payment->getId() .'.xlsx'
            )
        );

        return $response;
    }
}