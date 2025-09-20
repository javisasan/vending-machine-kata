<?php

namespace App\Vending\Domain\Machine\Dto;

use App\Vending\Domain\Cash\Dto\CoinCartridgeDto;

class ServiceDto
{
    private array $inventory = [];
    /** @var CoinCartridgeDto[] $exchange */
    private array $exchange = [];

    public function __construct(array $inventory, array $exchange)
    {
        $this->inventory = $inventory;
        $this->exchange = $exchange;
    }

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
