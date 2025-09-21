<?php

namespace App\Vending\Domain\Cash\Exception;

use App\SharedKernel\Domain\Exception\DomainException;

class InvalidCoinAmountException extends DomainException
{
    public function __construct()
    {
        parent::__construct('Invalid coin value amount', 1001);
    }
}
