<?php

namespace App\Vending\Application\Machine\Command;

use App\Vending\Application\Machine\Command\ServiceCommand;
use App\Vending\Domain\Cash\Dto\CoinCartridgeDto;
use App\Vending\Domain\Inventory\Dto\InventoryItemDto;
use App\Vending\Domain\Machine\Repository\VendingMachineRepositoryInterface;
use App\Vending\Domain\Machine\Service\SupplyServiceInterface;

class ServiceCommandHandler
{
    public function __construct(
        private SupplyServiceInterface $service,
        private VendingMachineRepositoryInterface $repository
    ) {
    }

    public function __invoke(ServiceCommand $command): void
    {
        $inventory = [];
        $exchange = [];

        foreach ($command->getInventory() as $inventoryItem) {
            $inventory[] = new InventoryItemDto(
                $inventoryItem['selector'],
                $inventoryItem['price'],
                $inventoryItem['quantity']
            );
        }

        foreach ($command->getExchange() as $exchangeItem) {
            $exchange[] = new CoinCartridgeDto(
                $exchangeItem['value'],
                $exchangeItem['quantity']
            );
        }

        $vendingMachine = $this->service->createVendingMachineFromService($inventory, $exchange);

        $this->repository->persist($vendingMachine);
    }
}
