<?php

namespace App\Vending\Domain\Cash\Exception;

use App\SharedKernel\Domain\Exception\DomainException;

class NotEnoughNumberOfCoinsException extends DomainException
{
    public function __construct()
    {
        parent::__construct('SharedKernel.Domain.exception.not_enough_number_of_coins', 1003);
    }
}
