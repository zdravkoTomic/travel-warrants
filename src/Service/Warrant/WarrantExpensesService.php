<?php

namespace App\Service\Warrant;

use App\Entity\Warrant;
use App\Repository\Codebook\CountryWageRepository;
use App\Repository\WarrantRepository;
use App\WebService\ExchangeRateService;

class WarrantExpensesService
{
    public function __construct(
        readonly WarrantRepository     $warrantRepository,
        readonly CountryWageRepository $countryWageRepository,
        readonly ExchangeRateService   $exchangeRateService
    ) {
    }

    public function advancesRefoundRequired(Warrant $warrant)
    {
        $totalExpenses = $this->getWarrantTotalExpensesInDomicileWageCurrency($warrant);
        $advance = $this->getAdvanceAmountInDomicileWageCurrency($warrant);

        return $advance > $totalExpenses;
    }

    public function getAdvanceAmountInDomicileWageCurrency(Warrant $warrant): float
    {
        $domicileWageCurrencyCode = $this->countryWageRepository->getDomesticCountryWage()->getCurrency()->getCode();

       return $warrant->getAdvancesCurrency()->getCode() !== $domicileWageCurrencyCode
           ? $this->exchangeRateService->convertByMiddleRateToDomicileCurrency(
               $warrant->getAdvancesAmount(),
               $warrant->getAdvancesCurrency()->getCode()
           )
           :$warrant->getAdvancesAmount();
    }

    private function getWarrantTotalExpensesInDomicileWageCurrency(Warrant $warrant): float
    {
        $warrantExpenses = $this->warrantRepository->findWarrantExpensesByWarrantId($warrant->getId());
        $warrantWages    = $this->warrantRepository->findWarrantCalculationWagesByWarrantId($warrant->getId());

        $expenses = array_merge($warrantExpenses, $warrantWages);

        $domicileWageCurrencyCode = $this->countryWageRepository->getDomesticCountryWage()->getCurrency()->getCode();

        $totalAmount = 0;

        foreach ($expenses as $key => $expense) {
            if ($expense['currency_code'] !== $domicileWageCurrencyCode) {
                $expenses[$key]['expense_amount'] = $this->exchangeRateService->convertByMiddleRateToDomicileCurrency(
                    $expenses[$key]['expense_amount'],
                    $expense['currency_code']
                );
                $expenses[$key]['currency_code'] = $domicileWageCurrencyCode;
            }

            $totalAmount += $expenses[$key]['expense_amount'];
        }

        return $totalAmount;
    }
}