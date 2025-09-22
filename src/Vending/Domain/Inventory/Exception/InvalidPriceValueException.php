<?php

namespace App\Vending\Domain\Inventory\Exception;

use App\SharedKernel\Domain\Exception\DomainException;

class InvalidPriceValueException extends DomainException
{
    public function __construct()
    {
        parent::__construct('Invalid value for price', 2002);
    }
}
