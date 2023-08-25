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
            'advancesRequired'         => $warrant->isAdvancesRequired(),
            'advancesAmount'           => $warrant->getAdvancesAmount(),
            'advancesCurrency'         => $warrant->getAdvancesCurrency(),
            'vehicleType'              => $warrant->getVehicleType()->getName(),
            'vehicleTypeDescription'   => $warrant->getVehicleDescription(),
            'travelTypeCode'           => $warrant->getTravelType()->getCode(),
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

    public function getCalculationReportData(Warrant $warrant): ?array
    {
        if (!$warrant->getWarrantCalculation()) {
            return [];
        }

        $totalExpenses = [];

        foreach ($warrant->getWarrantCalculation()->getWarrantCalculationWages() as $wage) {
            $this->addToTotalExpenses($totalExpenses, $wage->getAmount(), $wage->getCurrency()->getCode());
        }

        foreach ($warrant->getWarrantCalculation()->getWarrantCalculationExpenses() as $expense) {
            $this->addToTotalExpenses($totalExpenses, $expense->getAmount(), $expense->getCurrency()->getCode());
        }

        $advancesDeducted = false;

        if ($warrant->getAdvancesAmount() > 0) {
            foreach ($totalExpenses as $expense) {
                if ($expense['currency'] === $warrant->getAdvancesCurrency()->getCode()) {
                    $totalExpenses[$expense['currency']]['amount'] -= $warrant->getAdvancesAmount();
                    $advancesDeducted                  = true;
                }
            }
        }

        if (!$advancesDeducted) {
            $totalExpenses[$warrant->getAdvancesCurrency()->getCode()] = [
                'amount'   => $warrant->getAdvancesAmount(),
                'currency' => $warrant->getAdvancesCurrency()->getCode()
            ];
        }

        return [
            'departureDate'                => $warrant->getWarrantCalculation()->getDepartureDate()->format(
                'd.m.Y H:i:s'
            ),
            'returningDate'                => $warrant->getWarrantCalculation()->getReturningDate()->format(
                'd.m.Y H:i:s'
            ),
            'domicileCountryLeavingDate'   => $warrant->getWarrantCalculation()->getDomicileCountryLeavingDate(
            )->format(
                'd.m.Y H:i:s'
            ),
            'domicileCountryReturningDate' => $warrant->getWarrantCalculation()->getDomicileCountryReturningDate(
            )->format('d.m.Y H:i:s'),
            'warrantTravelItineraries'     => $warrant->getWarrantCalculation()->getWarrantTravelItineraries(),
            'warrantCalculationWages'      => $warrant->getWarrantCalculation()->getWarrantCalculationWages(),
            'warrantCalculationExpenses'   => $warrant->getWarrantCalculation()->getWarrantCalculationExpenses(),
            'travelReport'                 => $warrant->getWarrantCalculation()->getTravelReport(),
            'wageType'                     => $warrant->getWarrantCalculation()->getWageType(),
            'totalExpenses'                => $totalExpenses
        ];
    }

    private function addToTotalExpenses(&$result, $amount, $currency)
    {
        if (isset($result[$currency])) {
            $result[$currency]['amount'] += $amount;
        } else {
            $result[$currency] = ['amount' => $amount, 'currency' => $currency];
        }
    }
}