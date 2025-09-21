<?php

namespace Tests\Unit\Vending\Domain\Machine\Entity;

use App\Vending\Domain\Cash\Dto\CoinCartridgeDto;
use App\Vending\Domain\Cash\Entity\Cash;
use App\Vending\Domain\Cash\Entity\Coin;
use App\Vending\Domain\Inventory\Dto\InventoryItemDto;
use App\Vending\Domain\Inventory\Entity\Inventory;
use App\Vending\Domain\Machine\Dto\ServiceDto;
use App\Vending\Domain\Machine\Entity\VendingMachine;
use PHPUnit\Framework\TestCase;

class VendingMachineTest extends TestCase
{
    public function testCanCreateEmptyVendingMachine(): void
    {
        $sut = VendingMachine::create();

        $inventory = $sut->getInventory();
        $exchange = $sut->getExchange();
        $credit = $sut->getCredit();

        $this->assertSame(get_class($inventory), Inventory::class);
        $this->assertSame(0, count($inventory->getInventoryItems()));
        $this->assertSame(get_class($exchange), Cash::class);
        $this->assertSame(0, count($exchange->getCoinCartridges()));
        $this->assertSame(get_class($credit), Cash::class);
        $this->assertSame(0, count($credit->getCoinCartridges()));
    }

    public function testCanCreateVendingMachineFromServiceDto(): void
    {
        $inventoryItemDto1 = new InventoryItemDto('water', 0.65, 4);
        $inventoryItemDto2 = new InventoryItemDto('juice', 1.00, 5);
        $inventoryItemDto3 = new InventoryItemDto('soda', 1.50, 6);

        $coinCartridgeDto1 = new CoinCartridgeDto(0.25, 3);
        $coinCartridgeDto2 = new CoinCartridgeDto(0.05, 5);

        $serviceDto = new ServiceDto(
            [$inventoryItemDto1, $inventoryItemDto2, $inventoryItemDto3],
            [$coinCartridgeDto1, $coinCartridgeDto2]
        );

        $sut = VendingMachine::fromService($serviceDto);

        $inventory = $sut->getInventory();
        $exchange = $sut->getExchange();
        $credit = $sut->getCredit();

        $this->assertSame(get_class($inventory), Inventory::class);
        $this->assertSame(3, count($inventory->getInventoryItems()));
        $this->assertSame(6, $inventory->getQuantityForItemSelector('soda'));

        $inventoryItem = $inventory->getInventoryItems()['soda'];
        $this->assertSame('soda', $inventoryItem->getItem()->getSelector()->getName());
        $this->assertSame(1.5, $inventoryItem->getPrice()->getValue());
        $this->assertSame(6, $inventoryItem->getQuantity());

        $this->assertSame(get_class($exchange), Cash::class);
        $this->assertSame(2, count($exchange->getCoinCartridges()));
        $this->assertSame(3, $exchange->getQuantityForCoinValue(0.25));
        $this->assertSame(5, $exchange->getQuantityForCoinValue(0.05));
        $this->assertSame(get_class($credit), Cash::class);
        $this->assertSame(0, count($credit->getCoinCartridges()));
    }

    public function testCanAddCreditToVendindMachine(): void
    {
        $sut = VendingMachine::create();

        $coinFiveCents = Coin::create(0.05);
        $coinTenCents = Coin::create(0.1);
        $coinTwentyFiveCents = Coin::create(0.25);
        $coinHundredCents = Coin::create(1);

        $sut->addCredit($coinFiveCents);
        $sut->addCredit($coinTenCents);
        $sut->addCredit($coinTwentyFiveCents);
        $sut->addCredit($coinTwentyFiveCents);
        $sut->addCredit($coinHundredCents);

        $this->assertSame(1.65, $sut->getCredit()->getTotalAmount());
    }
}
