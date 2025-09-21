<?php

namespace App\Vending\Domain\Inventory\Exception;

use App\SharedKernel\Domain\Exception\DomainException;

class InvalidProductSelectorException extends DomainException
{
    public function __construct()
    {
        parent::__construct('SharedKernel.Domain.exception.invalid_product_selector', 2003);
    }
}
