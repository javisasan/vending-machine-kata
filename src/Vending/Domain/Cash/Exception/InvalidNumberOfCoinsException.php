<?php

namespace App\Vending\Domain\Cash\Exception;

use App\SharedKernel\Domain\Exception\DomainException;

class InvalidNumberOfCoinsException extends DomainException
{
    public function __construct()
    {
        parent::__construct('Invalid number of coins', 1002);
    }
}
