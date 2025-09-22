<?php

namespace App\Vending\Domain\Inventory\Exception;

use App\SharedKernel\Domain\Exception\DomainException;

class InvalidProductSelectorException extends DomainException
{
    public function __construct()
    {
        parent::__construct('Invalid product selector', 2003);
    }
}
