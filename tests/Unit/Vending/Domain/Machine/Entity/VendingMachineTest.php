<?php

namespace Tests\Unit\Vending\Domain\Machine\Entity;

use App\Vending\Domain\Cash\Dto\CoinCartridgeDto;
use App\Vending\Domain\Cash\Entity\Cash;
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

        $this->assertIsArray($inventory);
        $this->assertSame(get_class($exchange), Cash::class);
        $this->assertSame(0, count($exchange->getCoinCartridges()));
        $this->assertSame(get_class($credit), Cash::class);
        $this->assertSame(0, count($credit->getCoinCartridges()));
    }

    public function testCanCreateVendingMachineFromServiceDto(): void
    {
        $coinCartridgeDto1 = new CoinCartridgeDto(0.25, 3);
        $coinCartridgeDto2 = new CoinCartridgeDto(0.05, 5);

        $serviceDto = new ServiceDto([], [$coinCartridgeDto1, $coinCartridgeDto2]);

        $sut = VendingMachine::fromService($serviceDto);

        $inventory = $sut->getInventory();
        $exchange = $sut->getExchange();
        $credit = $sut->getCredit();

        $this->assertIsArray($inventory);
        $this->assertSame(get_class($exchange), Cash::class);
        $this->assertSame(2, count($exchange->getCoinCartridges()));
        $this->assertSame(3, $exchange->getQuantityForCoinValue(0.25));
        $this->assertSame(5, $exchange->getQuantityForCoinValue(0.05));
        $this->assertSame(get_class($credit), Cash::class);
        $this->assertSame(0, count($credit->getCoinCartridges()));
    }
}
