<?php

namespace App\Vending\Domain\Machine\Exception;

use App\SharedKernel\Domain\Exception\DomainException;

class NotEnoughChangeException extends DomainException
{
    public function __construct()
    {
        parent::__construct('Not enough exchange', 3002);
    }
}
