<?php

namespace Tests\Unit\Vending\Domain\Machine\Service;

use App\Vending\Domain\Cash\Dto\CoinCartridgeDto;
use App\Vending\Domain\Cash\Entity\Cash;
use App\Vending\Domain\Cash\Entity\Coin;
use App\Vending\Domain\Cash\Entity\CoinCartridge;
use App\Vending\Domain\Cash\Exception\NotEnoughChangeException;
use App\Vending\Domain\Inventory\Dto\InventoryItemDto;
use App\Vending\Domain\Inventory\Entity\Inventory;
use App\Vending\Domain\Inventory\Entity\InventoryItem;
use App\Vending\Domain\Inventory\Exception\ItemDoesNotExistException;
use App\Vending\Domain\Machine\Entity\VendingMachine;
use App\Vending\Domain\Machine\Exception\InsufficientCreditException;
use App\Vending\Domain\Machine\Exception\ProductIsDepletedException;
use App\Vending\Domain\Machine\Service\SupplyService;
use PHPUnit\Framework\TestCase;

class SupplyServiceTest extends TestCase
{
    public function testCanCreateVendingMachineFromMaintenanceService(): void
    {
        $inventoryItemDto = new InventoryItemDto('water', 0.65, 4);
        $CoinCartridgeDto = new CoinCartridgeDto(0.25, 3);

        $sut = new SupplyService();

        $result = $sut->createVendingMachineFromService([$inventoryItemDto], [$CoinCartridgeDto]);

        $this->assertSame(1, count($result->getInventory()->getInventoryItems()));
        $this->assertSame(4, $result->getInventory()->getQuantityForItemSelector('water'));
        $this->assertSame(1, count($result->getExchange()->getCoinCartridges()));
        $this->assertSame(3, $result->getExchange()->getQuantityForCoinValue(0.25));
        $this->assertSame(0.75, $result->getExchange()->getTotalAmount());
    }

    public function testCanCreateVendingMachineFromService(): void
    {
        $inventoryParam = new Inventory();
        $cashParam = new Cash();

        $inventoryItem1= InventoryItem::create('water', 0.65, 4);
        $inventoryItem2 = InventoryItem::create('juice', 1.00, 5);
        $inventoryItem3 = InventoryItem::create('soda', 1.50, 6);

        $inventoryParam->append($inventoryItem1);
        $inventoryParam->append($inventoryItem2);
        $inventoryParam->append($inventoryItem3);

        $coinCartridge1 = CoinCartridge::create(0.25, 3);
        $coinCartridge2 = CoinCartridge::create(0.05, 5);

        $cashParam->append($coinCartridge1);
        $cashParam->append($coinCartridge2);

        $sut = VendingMachine::fromService($inventoryParam, $cashParam);

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

    public function testCanEmptyCredit(): void
    {
        $sut = VendingMachine::create();

        $coinTwentyFiveCents = Coin::create(0.25);

        $sut->addCredit($coinTwentyFiveCents);

        $sut->emptyCredit();

        $this->assertSame(0.0, $sut->getCredit()->getTotalAmount());
    }

    public function testCanBuyItem(): void
    {
        $coin = Coin::create(1.0);

        $inventoryParam = new Inventory();
        $inventoryItem1= InventoryItem::create('water', 0.65, 4);
        $inventoryParam->append($inventoryItem1);

        $cashParam = new Cash();
        $coinCartridge1 = CoinCartridge::create(0.25, 1);
        $coinCartridge2 = CoinCartridge::create(0.10, 1);
        $cashParam->append($coinCartridge1);
        $cashParam->append($coinCartridge2);

        $sut = VendingMachine::fromService($inventoryParam, $cashParam);
        $sut->addCredit($coin);
        $sut->buyItem('water');

        $inventory = $sut->getInventory();
        $exchange = $sut->getExchange();
        $credit = $sut->getCredit();

        $this->assertSame(get_class($inventory), Inventory::class);
        $this->assertSame(1, count($inventory->getInventoryItems()));
        $this->assertSame(3, $inventory->getQuantityForItemSelector('water'));

        $this->assertSame(get_class($exchange), Cash::class);
        $this->assertSame(3, count($exchange->getCoinCartridges()));
        $this->assertSame(1, $exchange->getQuantityForCoinValue(1.0));
        $this->assertSame(0, $exchange->getQuantityForCoinValue(0.25));
        $this->assertSame(0, $exchange->getQuantityForCoinValue(0.05));
        $this->assertSame(get_class($credit), Cash::class);
        $this->assertSame(0, count($credit->getCoinCartridges()));
    }

    public function testCanNotBuyUnexistingItem(): void
    {
        $this->expectException(ItemDoesNotExistException::class);

        $sut = VendingMachine::create();
        $sut->buyItem('water');
    }

    public function testCanNotBuyDepletedProduct(): void
    {
        $this->expectException(ProductIsDepletedException::class);

        $inventoryItem= InventoryItem::create('juice', 1.0, 1);

        $sut = VendingMachine::create();
        $sut->getInventory()->append($inventoryItem);
        $sut->getInventory()->substractItem('juice');
        $sut->addCredit(Coin::create(1.0));

        $sut->buyItem('juice');
    }

    public function testCanGiveChangeForWater(): void
    {
        $inventoryParam = new Inventory();
        $inventoryItem1= InventoryItem::create('water', 0.65, 1);
        $inventoryParam->append($inventoryItem1);

        $cashParam = new Cash();
        $coinCartridge1 = CoinCartridge::create(0.05, 1);
        $coinCartridge2 = CoinCartridge::create(0.10, 1);
        $coinCartridge3 = CoinCartridge::create(0.25, 1);
        $coinCartridge4 = CoinCartridge::create(1.0, 1);
        $cashParam->append($coinCartridge1);
        $cashParam->append($coinCartridge2);
        $cashParam->append($coinCartridge3);
        $cashParam->append($coinCartridge4);

        $sut = VendingMachine::fromService($inventoryParam, $cashParam);

        $sut->addCredit(Coin::create(1.0));

        $change = $sut->calculateExchangeCoinsForItemBuy('water');

        $this->assertSame(2, count($change->getCoinCartridges()));
        $this->assertSame(1, $change->getQuantityForCoinValue(0.25));
        $this->assertSame(1, $change->getQuantityForCoinValue(0.1));
    }

    public function testEmptyChangeForJuice(): void
    {
        $inventoryParam = new Inventory();
        $inventoryItem1= InventoryItem::create('juice', 1.0, 1);
        $inventoryParam->append($inventoryItem1);

        $cashParam = new Cash();
        $coinCartridge1 = CoinCartridge::create(0.05, 1);
        $coinCartridge2 = CoinCartridge::create(0.10, 1);
        $coinCartridge3 = CoinCartridge::create(0.25, 1);
        $coinCartridge4 = CoinCartridge::create(1.0, 1);
        $cashParam->append($coinCartridge1);
        $cashParam->append($coinCartridge2);
        $cashParam->append($coinCartridge3);
        $cashParam->append($coinCartridge4);

        $sut = VendingMachine::fromService($inventoryParam, $cashParam);

        $sut->addCredit(Coin::create(1.0));

        $change = $sut->calculateExchangeCoinsForItemBuy('juice');

        $this->assertSame(0, count($change->getCoinCartridges()));
    }

    public function testCanNotGiveChangeBecauseInsufficientCredit(): void
    {
        $this->expectException(InsufficientCreditException::class);

        $inventoryItem= InventoryItem::create('water', 0.65, 1);

        $sut = VendingMachine::create();
        $sut->getInventory()->append($inventoryItem);

        $sut->calculateExchangeCoinsForItemBuy('water');
    }

    public function testCanNotGiveChangeBecauseNotEnoughChange(): void
    {
        $this->expectException(NotEnoughChangeException::class);

        $inventoryItem= InventoryItem::create('water', 0.65, 1);

        $sut = VendingMachine::create();
        $sut->getInventory()->append($inventoryItem);
        $sut->addCredit(Coin::create(1.0));

        $sut->calculateExchangeCoinsForItemBuy('water');
    }
}
