<?php

namespace Tests\Unit\Vending\Domain\Inventory\Entity\ValueObject;

use App\Vending\Domain\Inventory\Entity\ValueObject\ItemSelector;
use App\Vending\Domain\Inventory\Exception\InvalidProductSelectorException;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;

class ItemSelectorTest extends TestCase
{
    public static function provideValidValues(): array
    {
        return [
            [ItemSelector::SELECTOR_WATER],
            [ItemSelector::SELECTOR_JUICE],
            [ItemSelector::SELECTOR_SODA],
        ];
    }

    #[DataProvider('provideValidValues')]
    public function testCanCreateItemSelector(string $selector): void
    {
        $sut = new ItemSelector($selector);

        $this->assertSame($selector, $sut->getName());
    }

    public function testCanNotCreateItemSelectorWithInvalidName(): void
    {
        $this->expectException(InvalidProductSelectorException::class);

        new ItemSelector('beer');
    }
}
