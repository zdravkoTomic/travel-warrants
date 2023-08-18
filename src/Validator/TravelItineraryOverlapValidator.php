<?php

namespace App\Validator;

use App\Entity\Codebook\App\TravelType;
use App\Entity\WarrantCalculation;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;
use Symfony\Component\Validator\Exception\UnexpectedValueException;

class TravelItineraryOverlapValidator extends ConstraintValidator
{
    public function validate($warrantCalculation, Constraint $constraint): void
    {
        if (!$warrantCalculation instanceof WarrantCalculation) {
            throw new UnexpectedValueException($warrantCalculation, WarrantCalculation::class);
        }

        if (!$constraint instanceof TravelItineraryOverlap) {
            throw new UnexpectedValueException($constraint, TravelItineraryOverlap::class);
        }

        if ($warrantCalculation->getWarrant()->getTravelType()->getCode() === TravelType::DOMESTIC) {
            return;
        }

        if (!$warrantCalculation->getDomicileCountryLeavingDate()
            || !$warrantCalculation->getDomicileCountryReturningDate()
        ) {
            $this->context
                ->buildViolation($constraint->message)
                ->atPath('domicileCountryReturningDate')
                ->addViolation();
            return;
        }

        $ranges = [];

        $ranges[] = [$warrantCalculation->getDepartureDate(), $warrantCalculation->getDomicileCountryLeavingDate()];
        $ranges[] = [$warrantCalculation->getDomicileCountryReturningDate(), $warrantCalculation->getReturningDate()];

        foreach ($warrantCalculation->getWarrantTravelItineraries() as $itinerary) {
            $ranges[] = [$itinerary->getEnteredDate(), $itinerary->getExitedDate()];
        }

        $countRanges = count($ranges);

        foreach ($ranges as $i => $iValue) {
            for ($j = $i + 1; $j < $countRanges; $j++) {
                if ($this->rangesOverlap($iValue, $ranges[$j])) {
                    $this->context
                        ->buildViolation($constraint->message)
                        ->atPath('domicileCountryReturningDate')
                        ->addViolation();
                    return;
                }
            }
        }
    }

    private function rangesOverlap(array $range1, array $range2): bool
    {
        return $range1[0] < $range2[1] && $range2[0] < $range1[1];
    }
}