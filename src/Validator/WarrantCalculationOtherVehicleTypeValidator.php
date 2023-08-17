<?php

namespace App\Validator;

use App\Entity\Codebook\VehicleType;
use App\Entity\WarrantCalculation;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;
use Symfony\Component\Validator\Exception\UnexpectedValueException;

class WarrantCalculationOtherVehicleTypeValidator extends ConstraintValidator
{
    public function validate($warrantCalculation, Constraint $constraint): void
    {
        if (!$warrantCalculation instanceof WarrantCalculation) {
            throw new UnexpectedValueException($warrantCalculation, WarrantCalculation::class);
        }

        if (!$constraint instanceof WarrantCalculationOtherVehicleType) {
            throw new UnexpectedValueException($constraint, WarrantCalculationOtherVehicleType::class);
        }

        if ($warrantCalculation->getTravelVehicleType()->getCode() === VehicleType::OTHER_VEHICLE
        ) {
            if (!$warrantCalculation->getTravelVehicleDescription()) {
                $this->context->buildViolation($constraint->message)
                    ->atPath('travelVehicleDescription')
                    ->addViolation();
            }
        }
    }
}