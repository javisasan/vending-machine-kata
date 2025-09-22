<?php

namespace App\Vending\Domain\Machine\Exception;

use App\SharedKernel\Domain\Exception\DomainException;

class InsufficientCreditException extends DomainException
{
    public function __construct()
    {
        parent::__construct('Not enough credit', 3001);
    }
}
