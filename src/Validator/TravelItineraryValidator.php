<?php

namespace App\Validator;

use App\Entity\Codebook\App\TravelType;
use App\Entity\WarrantCalculation;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;
use Symfony\Component\Validator\Exception\UnexpectedValueException;

class TravelItineraryValidator extends ConstraintValidator
{
    private const TIME_SPENT_OUTSIDE_ANY_COUNTRY = 10;

    public function validate($warrantCalculation, Constraint $constraint): void
    {
        if (!$warrantCalculation instanceof WarrantCalculation) {
            throw new UnexpectedValueException($warrantCalculation, WarrantCalculation::class);
        }

        if (!$constraint instanceof TravelItinerary) {
            throw new UnexpectedValueException($constraint, TravelItinerary::class);
        }

        if ($warrantCalculation->getWarrant()->getTravelType()->getCode() === TravelType::DOMESTIC) {
            return;
        }

        $itineraries = $warrantCalculation->getWarrantTravelItineraries()->toArray();

        $departureItineraries = array_filter($itineraries, fn($i) => $i->isReturningData() === false);
        $returnItineraries    = array_filter($itineraries, fn($i) => $i->isReturningData() === true);

        usort($departureItineraries, fn($a, $b) => $a->getEnteredDate() <=> $b->getEnteredDate());
        usort($returnItineraries, fn($a, $b) => $a->getEnteredDate() <=> $b->getEnteredDate());

        if (!$this->isValidTransition($departureItineraries) || !$this->isValidTransition($returnItineraries)) {
            $this->context->buildViolation($constraint->message)
                ->atPath('warrantTravelItineraries')
                ->addViolation();
        }
    }

    private function isValidTransition(array $itineraries): bool
    {
        $itineraryCount = count($itineraries);

        for ($i = 1; $i < $itineraryCount; $i++) {
            $diff    = $itineraries[$i]->getEnteredDate()->diff($itineraries[$i - 1]->getExitedDate());
            $minutes = ($diff->days * 24 * 60) + ($diff->h * 60) + $diff->i;

            if ($minutes > self::TIME_SPENT_OUTSIDE_ANY_COUNTRY) {
                return false;
            }
        }

        return true;
    }
}