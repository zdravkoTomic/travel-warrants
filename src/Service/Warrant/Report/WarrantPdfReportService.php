<?php

namespace App\Service\Warrant\Report;

use App\Entity\Warrant;
use Dompdf\Dompdf;
use Picqer\Barcode\BarcodeGeneratorHTML;

class WarrantPdfReportService
{
    public function getInitialReportData(Warrant $warrant): array
    {
        return [
            'barcode'                  => $this->generateBarcode($warrant->getCode()),
            'code'                     => $warrant->getCode(),
            'employeeName'             => $warrant->getEmployee()->getName(),
            'employeeSurname'          => $warrant->getEmployee()->getSurname(),
            'warrantDepartment'        => $warrant->getDepartment()->getName(),
            'createdAt'                => $warrant->getCreatedAt()->format('d.m.Y'),
            'employeeWorkPosition'     => $warrant->getEmployee()->getWorkPosition()->getName(),
            'departureDate'            => $warrant->getDepartureDate()->format('d.m.Y'),
            'departurePoint'           => $warrant->getDeparturePoint(),
            'destination'              => $warrant->getDestination(),
            'destinationCountry'       => $warrant->getDestinationCountry()->getName(),
            'travelPurposeDescription' => $warrant->getTravelPurposeDescription(),
            'expectedTravelDuration'   => $warrant->getExpectedTravelDuration(),
            'wageAmount'               => $warrant->getWageAmount(),
            'wageCurrency'             => $warrant->getWageCurrency()->getCode(),
            'getAdvancesAmount'        => $warrant->getAdvancesAmount(),
            'vehicleType'              => $warrant->getVehicleType()->getName(),
            'vehicleTypeDescription'   => $warrant->getVehicleDescription(),
        ];
    }

    private function generateBarcode(string $warrantCode): string
    {
        $barcodeGenerator = new BarcodeGeneratorHTML();

        return $barcodeGenerator->getBarcode($warrantCode, $barcodeGenerator::TYPE_CODE_128);
    }

    public function createInitialReportContent($html): ?string
    {
        $dompdf = new Dompdf(['isHtml5ParserEnabled' => true, 'defaultFont' => 'dejavu sans']);
        $dompdf->loadHtml($html);
        $dompdf->render();

        return $dompdf->output();
    }
}