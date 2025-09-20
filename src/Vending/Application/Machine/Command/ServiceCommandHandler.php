<?php

namespace App\Vending\Application\Machine\Command;

use App\Vending\Application\Machine\Command\ServiceCommand;
use App\Vending\Domain\Cash\Dto\CoinCartridgeDto;
use App\Vending\Domain\Machine\Dto\ServiceDto;
use App\Vending\Domain\Machine\Entity\VendingMachine;
use App\Vending\Domain\Machine\Repository\VendingMachineRepositoryInterface;

class ServiceCommandHandler
{
    public function __construct(private VendingMachineRepositoryInterface $repository)
    {
    }

    public function __invoke(ServiceCommand $command): void
    {
        $inventory = $command->getInventory();
        $exchange = [];

        foreach ($command->getExchange() as $exchangeItem) {
            $exchange[] = new CoinCartridgeDto($exchangeItem['value'], $exchangeItem['quantity']);
        }


        $serviceDto = new ServiceDto($inventory, $exchange);

        $vendingMachine = VendingMachine::fromService($serviceDto);

        $this->repository->persist($vendingMachine);
    }
}
