<?php

namespace App\Service\Warrant\WarrantCalculation;

use ApiPlatform\Exception\InvalidValueException;
use App\Entity\Codebook\App\TravelType;
use App\Entity\Codebook\VehicleType;
use App\Entity\WageType;
use App\Entity\WarrantCalculation;
use App\Entity\WarrantCalculationExpense;
use App\Entity\WarrantCalculationWage;
use App\Exception\RecordNotFoundException;
use App\Repository\Codebook\CountryWageRepository;
use App\Repository\Codebook\ExpenseTypeRepository;
use App\Repository\Codebook\PredefinedExpenseRepository;
use App\WebService\ExchangeRateService;
use DateTime;
use Doctrine\ORM\NonUniqueResultException;
use InvalidArgumentException;

class WarrantCalculationService
{
    private CountryWageRepository $countryWageRepository;
    private ExpenseTypeRepository $expenseTypeRepository;
    private PredefinedExpenseRepository $predefinedExpenseRepository;
    private ExchangeRateService $exchangeRateService;

    public function __construct(
        CountryWageRepository       $countryWageRepository,
        ExpenseTypeRepository       $expenseTypeRepository,
        PredefinedExpenseRepository $predefinedExpenseRepository,
        ExchangeRateService         $exchangeRateService
    ) {
        $this->countryWageRepository       = $countryWageRepository;
        $this->expenseTypeRepository       = $expenseTypeRepository;
        $this->predefinedExpenseRepository = $predefinedExpenseRepository;
        $this->exchangeRateService         = $exchangeRateService;
    }

    public function setTravelDuration(WarrantCalculation $warrantCalculation)
    {
        if (!$warrantCalculation->getDepartureDate() || !$warrantCalculation->getReturningDate()) {
            throw new InvalidValueException('Invalid date value provided');
        }

        $interval = $warrantCalculation->getDepartureDate()->diff($warrantCalculation->getReturningDate());

        $travelDuration = $interval->days * 24 * 60 + $interval->h * 60 + $interval->i;

        $warrantCalculation->setTravelDuration($travelDuration);
    }

    /**
     * @throws RecordNotFoundException
     * @throws NonUniqueResultException
     */
    public function setWarrantCalculationWage(WarrantCalculation $warrantCalculation)
    {
        if ($warrantCalculation->getWarrant()->getTravelType()->getCode() === TravelType::DOMESTIC) {
            $this->setDomesticTravelWages($warrantCalculation);
        } else {
            $this->setInternationalTravelWages($warrantCalculation);
        }
    }

    public function setDomesticTravelWages(WarrantCalculation $warrantCalculation)
    {
        $interval   = $warrantCalculation->getDepartureDate()->diff($warrantCalculation->getReturningDate());
        $totalHours = ($interval->days * 24) + $interval->h;

        $domesticNumberOfWages = $this->getNumberOfDomesticWages($totalHours);
        $domesticCountryWage   = $this->countryWageRepository->getDomesticCountryWage();
        $totalWageAmount       = $domesticNumberOfWages * $domesticCountryWage->getAmount();
        $deductedWageAmount    = $this->getDeductedWageAmount($totalWageAmount, $warrantCalculation->getWageType());

        $domesticWage = new WarrantCalculationWage();

        $domesticWage->setWarrantCalculation($warrantCalculation)
            ->setCountry($domesticCountryWage->getCountry())
            ->setAmount($deductedWageAmount)
            ->setCurrency($domesticCountryWage->getCurrency())
            ->setNumberOfWages($domesticNumberOfWages);

        $warrantCalculation->addWarrantCalculationWage($domesticWage);
    }

    private function getNumberOfDomesticWages($totalHours): float|int
    {
        $fullDays       = (int)($totalHours / 24);
        $remainingHours = $totalHours % 24;

        $totalWageNumber = $fullDays;

        if ($remainingHours >= 8 && $remainingHours < 12) {
            $totalWageNumber += 0.5;
        } elseif ($remainingHours >= 12) {
            ++$totalWageNumber;
        }

        return $totalWageNumber;
    }

    private function getDeductedWageAmount(float $fullWageAmount, WageType $wageType)
    {
        $wageAmount = 0;

        if ($wageType->getCode() === WageType::FULL_WAGE) {
            $wageAmount = $fullWageAmount;
        } else if ($wageType->getCode() === WageType::ONE_MEAL_COVERED
            || $wageType->getCode() === WageType::TWO_MEAL_COVERED
        ) {
            $wageAmount = $fullWageAmount - (($wageType->getWagePercentageDeduction() / 100) * $fullWageAmount);
        }

        return round($wageAmount, 2);
    }

    /**
     * @throws RecordNotFoundException
     * @throws NonUniqueResultException
     */
    public function setInternationalTravelWages(WarrantCalculation $warrantCalculation)
    {
        $domesticIntervalPartOne =
            $warrantCalculation->getDepartureDate()->diff($warrantCalculation->getDomicileCountryLeavingDate());
        $hoursSegmentOne         = ($domesticIntervalPartOne->days * 24) + $domesticIntervalPartOne->h;

        $domesticIntervalPartTwo =
            $warrantCalculation->getDomicileCountryReturningDate()->diff($warrantCalculation->getReturningDate());
        $hoursSegmentTwo         = ($domesticIntervalPartTwo->days * 24) + $domesticIntervalPartTwo->h;

        $totalHours = $hoursSegmentOne + $hoursSegmentTwo;

        $domesticNumberOfWages = $this->getNumberOfDomesticWages($totalHours);

        $countryWages = [];

        if ($domesticNumberOfWages) {
            $domesticWage        = new WarrantCalculationWage();
            $domesticCountryWage = $this->countryWageRepository->getDomesticCountryWage();
            $totalWageAmount     = $domesticNumberOfWages * $domesticCountryWage->getAmount();
            $deductedWageAmount  = $this->getDeductedWageAmount($totalWageAmount, $warrantCalculation->getWageType());

            $domesticWage->setWarrantCalculation($warrantCalculation)
                ->setCountry($domesticCountryWage->getCountry())
                ->setAmount($deductedWageAmount)
                ->setCurrency($domesticCountryWage->getCurrency())
                ->setNumberOfWages($domesticNumberOfWages);

            $countryWages[] = $domesticWage;

            $warrantCalculation->addWarrantCalculationWage($domesticWage);
        }


        $itineraries = $warrantCalculation->getWarrantTravelItineraries();

        $countriesTime = [];

        // Group hours spent on traveling countries
        foreach ($itineraries as $itinerary) {
            $countryId  = $itinerary->getCountry()->getId();
            $interval   = $itinerary->getEnteredDate()->diff($itinerary->getExitedDate());
            $hoursSpent = ($interval->days * 24) + $interval->h;

            if (!isset($countriesTime[$countryId])) {
                $countriesTime[$countryId] = 0;
            }

            $countriesTime[$countryId] += $hoursSpent;
        }

        $carryHours = 0;

        // Insert wages for traveling countries where time spent was over 12 hours
        foreach ($countriesTime as $key => $hours) {
            $hours      += $carryHours;
            $carryHours = 0;

            if ($hours < 12) {
                $carryHours = $hours;
                continue;
            }

            $fullDays       = (int)($hours / 24);
            $remainingHours = $hours % 24;

            $numberOfCountryWages = $fullDays;

            if ($remainingHours >= 8 && $remainingHours < 12) {
                $numberOfCountryWages += 0.5;
            } elseif ($remainingHours >= 12) {
                ++$numberOfCountryWages;
            }

            $travelCountryWage      = new WarrantCalculationWage();
            $countryWage            = $this->countryWageRepository->getCountryWageByCountryId($key);
            $totalCountryWageAmount = $numberOfCountryWages * $countryWage->getAmount();
            $deductedWageAmount     =
                $this->getDeductedWageAmount($totalCountryWageAmount, $warrantCalculation->getWageType());

            $travelCountryWage->setWarrantCalculation($warrantCalculation)
                ->setCountry($countryWage->getCountry())
                ->setAmount($deductedWageAmount)
                ->setCurrency($countryWage->getCurrency())
                ->setNumberOfWages($numberOfCountryWages);

            $countryWages[] = $travelCountryWage;
        }

        $destinationEntry = null;
        $destinationExit  = null;

        foreach ($itineraries as $itinerary) {
            if (!$itinerary->isReturningData()) {
                if (!$destinationEntry || $itinerary->getExitedDate() > $destinationEntry) {
                    $destinationEntry = $itinerary->getExitedDate();
                }
            } else {
                if (!$destinationExit || $itinerary->getEnteredDate() < $destinationExit) {
                    $destinationExit = $itinerary->getEnteredDate();
                }
            }
        }

        if (!$destinationEntry) {
            $destinationEntry = $warrantCalculation->getDomicileCountryLeavingDate();
        }

        if (!$destinationExit) {
            $destinationExit = $warrantCalculation->getDomicileCountryReturningDate();
        }

        if ($destinationEntry && $destinationExit) {
            $interval         = $destinationExit->diff($destinationEntry);
            $destinationHours = ($interval->days * 24) + $interval->h;

            // Dodajemo neobračunate sate
            $destinationHours += $carryHours;

            $fullDays       = (int)($destinationHours / 24);
            $remainingHours = $destinationHours % 24;

            $numberOfDestinationWages = $fullDays;

            if ($remainingHours >= 8 && $remainingHours < 12) {
                $numberOfDestinationWages += 0.5;
            } elseif ($remainingHours >= 12) {
                ++$numberOfDestinationWages;
            }

            $destinationCountryWage = new WarrantCalculationWage();
            $countryWage            = $this->countryWageRepository->getCountryWageByCountryId(
                $warrantCalculation->getWarrant()->getDestinationCountry()->getId()
            );

            $totalCountryWageAmount = $numberOfDestinationWages * $countryWage->getAmount();
            $deductedWageAmount     =
                $this->getDeductedWageAmount($totalCountryWageAmount, $warrantCalculation->getWageType());

            $destinationCountryWage->setWarrantCalculation($warrantCalculation)
                ->setCountry($countryWage->getCountry())
                ->setAmount($deductedWageAmount)
                ->setCurrency($countryWage->getCurrency())
                ->setNumberOfWages($numberOfDestinationWages);

            $countryWages[] = $destinationCountryWage;
        }

        foreach ($countryWages as $countryWage) {
            $warrantCalculation->addWarrantCalculationWage($countryWage);
        }
    }

    public function getDifferenceBetweenDateTimesInMinutes(DateTime $startDate, DateTime $endDate): float|int
    {
        $interval = $startDate->diff($endDate);

        return $interval->days * 24 * 60 + $interval->h * 60 + $interval->i;
    }

    public function setWarrantCalculationVehicleExpense(WarrantCalculation $warrantCalculation): void
    {
        if ($warrantCalculation->getTravelVehicleType()->getCode() === VehicleType::PERSONAL_VEHICLE
            || $warrantCalculation->getTravelVehicleType()->getCode() === VehicleType::OFFICAL_PERSONAL_VEHICLE
        ) {
            $expenseType = $this->expenseTypeRepository->findOneBy(
                ['code' => $warrantCalculation->getTravelVehicleType()->getCode()]
            );

            if (!$expenseType) {
                throw new InvalidArgumentException('Invalid expense type provided');
            }

            $amount   = $this->calculateVehicleAmount($warrantCalculation);
            $currency = $this->countryWageRepository->getDomesticCountryWage()->getCurrency();

            $vehicleExpense = new WarrantCalculationExpense();

            $vehicleExpense->setExpenseType($expenseType)
                ->setWarrantCalculation($warrantCalculation)
                ->setCurrency($currency)
                ->setOriginalCurrency($currency)
                ->setAmount($amount)
                ->setOriginalAmount($amount)
                ->setDescription($expenseType->getName());

            $warrantCalculation->addWarrantCalculationExpense($vehicleExpense);
        }
    }

    private function calculateVehicleAmount(WarrantCalculation $warrantCalculation): float|int
    {
        $totalKilometers = $warrantCalculation->getOdometerEnd() - $warrantCalculation->getOdometerStart();

        $predefinedVehicleExpense = $this->predefinedExpenseRepository->getActiveByExpenseCode(
            $warrantCalculation->getTravelVehicleType()->getCode()
        );

        return $totalKilometers * $predefinedVehicleExpense->getAmount();
    }

    public function setExpenseAmountAndCurrency(WarrantCalculationExpense $warrantCalculationExpense): void
    {
        $domesticCurrency        = $this->countryWageRepository->getDomesticCountryWage()->getCurrency();
        $destinationWageCurrency = $this->countryWageRepository->getCountryWageByCountryId(
            $warrantCalculationExpense->getWarrantCalculation()->getWarrant()->getDestinationCountry()->getId()
        )->getCurrency();

        if (!$domesticCurrency && !$destinationWageCurrency) {
            throw new InvalidArgumentException('Invalid currency provided');
        }

        if ($warrantCalculationExpense->getOriginalCurrency()->getCode() !== $domesticCurrency->getCode()
            && $warrantCalculationExpense->getOriginalCurrency()->getCode() !== $destinationWageCurrency->getCode()
        ) {
            $convertedAmountToDomicileCurrency = $this->exchangeRateService->convertByMiddleRateToDomicileCurrency(
                $warrantCalculationExpense->getOriginalAmount(),
                $warrantCalculationExpense->getOriginalCurrency()->getCode()
            );

            $warrantCalculationExpense->setAmount($convertedAmountToDomicileCurrency)
                ->setCurrency($domesticCurrency)
                ->setExpenseType($warrantCalculationExpense->getExpenseType())
                ->setWarrantCalculation($warrantCalculationExpense->getWarrantCalculation())
                ->setOriginalCurrency($warrantCalculationExpense->getOriginalCurrency())
                ->setOriginalAmount($warrantCalculationExpense->getOriginalAmount())
                ->setDescription($warrantCalculationExpense->getDescription());
        } else {
            $warrantCalculationExpense->setAmount($warrantCalculationExpense->getOriginalAmount())
                ->setCurrency($warrantCalculationExpense->getOriginalCurrency())
                ->setExpenseType($warrantCalculationExpense->getExpenseType())
                ->setWarrantCalculation($warrantCalculationExpense->getWarrantCalculation())
                ->setOriginalCurrency($warrantCalculationExpense->getOriginalCurrency())
                ->setOriginalAmount($warrantCalculationExpense->getOriginalAmount())
                ->setDescription($warrantCalculationExpense->getDescription());
        }
    }
}