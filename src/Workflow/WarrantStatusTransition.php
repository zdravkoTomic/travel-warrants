<?php

namespace App\Workflow;

use App\Entity\Codebook\App\WarrantStatus;
use InvalidArgumentException;

final class WarrantStatusTransition
{
    public const TO_NEW                           = 'to_new';
    public const TO_APPROVING                     = 'to_approving';
    public const TO_APPROVING_ADVANCE_PAYMENT     = 'to_approving_advance_payment';
    public const TO_ADVANCE_IN_PAYMENT            = 'to_advance_in_payment';
    public const TO_CALCULATION_EDIT              = 'to_calculation_edit';
    public const TO_APPROVING_CALCULATION         = 'to_approving_calculation';
    public const TO_APPROVING_CALCULATION_PAYMENT = 'to_approving_calculation_payment';
    public const TO_CALCULATION_IN_PAYMENT        = 'to_calculation_in_payment';
    public const TO_CLOSED                        = 'to_closed';
    public const TO_CANCELLED                     = 'to_cancelled';

    public static function warrantStatus(WarrantStatus $status): string
    {
        $transition = 'to_' . strtolower($status->getCode());

        if (null === constant(__CLASS__ . '::' . strtoupper($transition))) {
            throw new InvalidArgumentException(
                sprintf('Unable to find transition for warrant status %s', $status->getCode())
            );
        }

        return $transition;
    }
}