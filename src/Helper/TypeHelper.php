<?php

namespace App\Helper;

final class TypeHelper
{
    public static function getType($var): string
    {
        return is_object($var) ? get_class($var) : gettype($var);
    }
}