<?php

namespace Tests\Unit\Vending\Application\Machine\Command;

use App\Vending\Application\Machine\Command\BuyItemCommand;
use PHPUnit\Framework\TestCase;

class BuyItemCommandTest extends TestCase
{
    public function testCanCreateCommand(): void
    {
        $selector = 'water';

        $sut = new BuyItemCommand($selector);

        $this->assertSame($selector, $sut->getSelector());
    }
}
