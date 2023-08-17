<?php

namespace App\Validator;

use Symfony\Component\Validator\Constraint;

#[\Attribute]
class WarrantCalculationOtherVehicleType extends Constraint
{
    public string $message = 'Kod odabira vrste vozila "ostalo", potrebno je unijeti opis vozila';

    public function getTargets(): string
    {
        return self::CLASS_CONSTRAINT;
    }
}