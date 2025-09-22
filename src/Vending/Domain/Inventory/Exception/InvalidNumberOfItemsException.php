<?php

namespace App\Vending\Domain\Inventory\Exception;

use App\SharedKernel\Domain\Exception\DomainException;

class InvalidNumberOfItemsException extends DomainException
{
    public function __construct()
    {
        parent::__construct('Invalid number of items', 2001);
    }
}
