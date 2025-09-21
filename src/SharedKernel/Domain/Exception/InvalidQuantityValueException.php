<?php

namespace App\SharedKernel\Domain\Exception;

use App\SharedKernel\Domain\Exception\DomainException;

class InvalidQuantityValueException extends DomainException
{
    public function __construct()
    {
        parent::__construct('SharedKernel.Domain.exception.invalid_quantity_value', 9001);
    }
}
