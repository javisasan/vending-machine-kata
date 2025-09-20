<?php

namespace App\Vending\Domain\Cash\Exception;

use App\SharedKernel\Domain\Exception\DomainException;

class InvalidNumberOfCoinsException extends DomainException
{
    public function __construct()
    {
        parent::__construct('SharedKernel.Domain.exception.invalid_number_of_coins', 1002);
    }
}
