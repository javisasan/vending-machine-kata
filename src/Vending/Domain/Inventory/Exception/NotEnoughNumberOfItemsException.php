<?php

namespace App\Vending\Domain\Inventory\Exception;

use App\SharedKernel\Domain\Exception\DomainException;

class NotEnoughNumberOfItemsException extends DomainException
{
    public function __construct()
    {
        parent::__construct('Not enough number of items', 2004);
    }
}
