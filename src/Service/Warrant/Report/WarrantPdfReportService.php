<?php

namespace App\Service\Warrant\Report;

use App\Entity\Warrant;
use Dompdf\Dompdf;

class WarrantPdfReportService
{
    public function getInitialReporData(Warrant $warrant): array
    {
        return [
            'employeeName'         => $warrant->getEmployee()->getName(),
            'employeeSurname'      => $warrant->getEmployee()->getSurname()
        ];
    }

    public function createInitialReportContent($html): ?string
    {
        $dompdf = new Dompdf();
        $dompdf->loadHtml($html);
        $dompdf->render();

        return $dompdf->output();
    }
}