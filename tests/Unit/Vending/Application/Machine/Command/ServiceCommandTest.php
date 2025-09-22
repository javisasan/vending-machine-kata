<?php

namespace Tests\Unit\Vending\Application\Machine\Command;

use App\Vending\Application\Machine\Command\ServiceCommand;
use PHPUnit\Framework\TestCase;

class ServiceCommandTest extends TestCase
{
    public function testCanCreateCommand(): void
    {
        $inventory = ['one', 'two'];
        $exchange = ['three', 'four'];

        $sut = new ServiceCommand($inventory, $exchange);

        $this->assertSame($inventory, $sut->getInventory());
        $this->assertSame($exchange, $sut->getExchange());
    }
}
