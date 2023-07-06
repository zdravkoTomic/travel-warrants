<?php

namespace App\Exception;

use Doctrine\ORM\Exception\ORMException;

class RecordNotFoundException extends ORMException
{
    public function __construct(string $entityClassPath)
    {
        parent::__construct(sprintf('No record could be found: %s', $entityClassPath));
    }
}