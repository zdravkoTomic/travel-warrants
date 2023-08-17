<?php

namespace App\Validator;

use Symfony\Component\Validator\Constraint;

#[\Attribute]
class TravelItinerary extends Constraint
{
    public string $message = 'Neispravan unos vremena putovanja, provjerite svoj unos';

    public function getTargets(): string
    {
        return self::CLASS_CONSTRAINT;
    }
}