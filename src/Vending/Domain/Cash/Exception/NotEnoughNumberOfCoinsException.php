<?php

namespace App\Vending\Domain\Cash\Exception;

use App\SharedKernel\Domain\Exception\DomainException;

class NotEnoughNumberOfCoinsException extends DomainException
{
    public function __construct()
    {
        parent::__construct('Not enough number of coins', 1003);
    }
}
