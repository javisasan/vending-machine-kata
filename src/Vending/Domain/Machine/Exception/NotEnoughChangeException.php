<?php

namespace App\Vending\Domain\Machine\Exception;

use App\SharedKernel\Domain\Exception\DomainException;

class NotEnoughChangeException extends DomainException
{
    public function __construct()
    {
        parent::__construct('SharedKernel.Domain.exception.not_enough_change_exception', 3002);
    }
}
