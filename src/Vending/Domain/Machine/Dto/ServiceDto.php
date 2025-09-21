<?php

namespace App\Vending\Domain\Machine\Dto;

use App\Vending\Domain\Cash\Dto\CoinCartridgeDto;
use App\Vending\Domain\Inventory\Dto\InventoryItemDto;

class ServiceDto
{
    /** @var InventoryItemDto[] $inventory */
    private array $inventory = [];
    /** @var CoinCartridgeDto[] $exchange */
    private array $exchange = [];

    public function __construct(array $inventory, array $exchange)
    {
        $this->inventory = $inventory;
        $this->exchange = $exchange;
    }

    /**
     * @return InventoryItemDto[]
     */
    public function getInventory(): array
    {
        return $this->inventory;
    }

    /**
     * @return CoinCartrigdeDto[]
     */
    public function getExchange(): array
    {
        return $this->exchange;
    }
}
