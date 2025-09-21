<?php

namespace Tests\Unit\Vending\Domain\Cash\Dto;

use App\Vending\Domain\Cash\Dto\CoinCartridgeDto;
use PHPUnit\Framework\TestCase;

class CoinCartridgeDtoTest extends TestCase
{
    public function testCanCreateDto(): void
    {
        $value = 0.65;
        $quantity = 3;

        $sut = new CoinCartridgeDto($value, $quantity);

        $this->assertSame($value, $sut->getValue());
        $this->assertSame($quantity, $sut->getQuantity());
    }
}
