<?php

namespace Tests\Unit\Vending\Domain\Cash\Entity\ValueObject;

use App\Vending\Domain\Cash\Entity\ValueObject\CoinValue;
use App\Vending\Domain\Cash\Exception\InvalidCoinAmountException;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;

class CoinValueTest extends TestCase
{
    public static function provideValidValues(): array
    {
        return [
            [CoinValue::FIVE_CENTS],
            [CoinValue::TEN_CENTS],
            [CoinValue::TWENTY_FIVE_CENTS],
            [CoinValue::ONE_HUNDRED_CENTS],
        ];
    }

    #[DataProvider('provideValidValues')]
    public function testCanCreateCoinValue(float $value): void
    {
        $sut = new CoinValue($value);

        $this->assertSame($value, $sut->getValue());
    }

    public function testCanNotCreateCoinValueWithInvalidValue(): void
    {
        $this->expectException(InvalidCoinAmountException::class);

        new CoinValue(0.15);
    }
}
