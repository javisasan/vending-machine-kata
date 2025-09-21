<?php

namespace App\Vending\Domain\Machine\Exception;

use App\SharedKernel\Domain\Exception\DomainException;

class InsufficientCreditException extends DomainException
{
    public function __construct()
    {
        parent::__construct('SharedKernel.Domain.exception.insufficient_credit_exception', 3001);
    }
}
