<?php

namespace App\Vending\Domain\Inventory\Exception;

use App\SharedKernel\Domain\Exception\DomainException;

class InvalidNumberOfItemsException extends DomainException
{
    public function __construct()
    {
        parent::__construct('SharedKernel.Domain.exception.invalid_number_of_items', 2001);
    }
}
