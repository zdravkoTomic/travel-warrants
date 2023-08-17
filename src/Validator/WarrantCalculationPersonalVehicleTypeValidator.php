<?php

namespace App\Validator;

use App\Entity\Codebook\VehicleType;
use App\Entity\WarrantCalculation;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;
use Symfony\Component\Validator\Exception\UnexpectedValueException;

class WarrantCalculationPersonalVehicleTypeValidator extends ConstraintValidator
{
    public function validate($warrantCalculation, Constraint $constraint): void
    {
        if (!$warrantCalculation instanceof WarrantCalculation) {
            throw new UnexpectedValueException($warrantCalculation, WarrantCalculation::class);
        }

        if (!$constraint instanceof WarrantCalculationPersonalVehicleType) {
            throw new UnexpectedValueException($constraint, WarrantCalculationPersonalVehicleType::class);
        }

        if ($warrantCalculation->getTravelVehicleType()->getCode() === VehicleType::OFFICAL_PERSONAL_VEHICLE
            || $warrantCalculation->getTravelVehicleType()->getCode() === VehicleType::PERSONAL_VEHICLE
            || $warrantCalculation->getTravelVehicleType()->getCode() === VehicleType::OFFICAL_VEHICLE
        ) {
            if (!$warrantCalculation->getOdometerStart()
                || !$warrantCalculation->getOdometerEnd()
                || !$warrantCalculation->getTravelVehicleRegistration()
                || !$warrantCalculation->getTravelVehicleBrand()
            ) {
                $this->context->buildViolation($constraint->message)
                    ->atPath('travelVehicleType')
                    ->addViolation();
            }
        }
    }
}