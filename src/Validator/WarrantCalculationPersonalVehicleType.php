<?php

namespace App\Validator;

use Symfony\Component\Validator\Constraint;

#[\Attribute]
class WarrantCalculationPersonalVehicleType extends Constraint
{
    public string $message = 'Kod korištenja osobnog ili službenog vozila potrebno je ispuniti početne i završne kilometre odometra, te marku i marku automobila';

    public function getTargets(): string
    {
        return self::CLASS_CONSTRAINT;
    }
}