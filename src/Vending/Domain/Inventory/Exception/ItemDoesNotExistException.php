<?php

namespace App\Vending\Domain\Inventory\Exception;

use App\SharedKernel\Domain\Exception\DomainException;

class ItemDoesNotExistException extends DomainException
{
    public function __construct()
    {
        parent::__construct('Item dos not exist', 2005);
    }
}
