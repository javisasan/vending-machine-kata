<?php

namespace App\Vending\Domain\Inventory\Exception;

use App\SharedKernel\Domain\Exception\DomainException;

class InvalidPriceValueException extends DomainException
{
    public function __construct()
    {
        parent::__construct('SharedKernel.Domain.exception.invalid_value_for_price', 2002);
    }
}
