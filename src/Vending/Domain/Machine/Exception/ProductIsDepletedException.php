<?php

namespace App\Vending\Domain\Machine\Exception;

use App\SharedKernel\Domain\Exception\DomainException;

class ProductIsDepletedException extends DomainException
{
    public function __construct()
    {
        parent::__construct('Product is depleted', 3003);
    }
}
