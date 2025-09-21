<?php

namespace App\Vending\Domain\Inventory\Exception;

use App\SharedKernel\Domain\Exception\DomainException;

class NotEnoughNumberOfItemsException extends DomainException
{
    public function __construct()
    {
        parent::__construct('SharedKernel.Domain.exception.not_enough_number_of_items', 2004);
    }
}
