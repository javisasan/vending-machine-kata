<?php

namespace App\Vending\Domain\Machine\Repository;

use App\Vending\Domain\Machine\Entity\VendingMachine;

interface VendingMachineRepositoryInterface
{
    public function create(): VendingMachine;
    public function get(): VendingMachine;
    public function persist(VendingMachine $vendingMachine): void;
}
