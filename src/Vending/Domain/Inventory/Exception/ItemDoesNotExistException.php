<?php

namespace App\Vending\Domain\Inventory\Exception;

use App\SharedKernel\Domain\Exception\DomainException;

class ItemDoesNotExistException extends DomainException
{
    public function __construct()
    {
        parent::__construct('SharedKernel.Domain.exception.item_does_not_exist', 2005);
    }
}
