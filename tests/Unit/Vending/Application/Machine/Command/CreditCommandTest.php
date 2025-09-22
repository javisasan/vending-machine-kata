<?php

namespace Tests\Unit\Vending\Application\Machine\Command;

use App\Vending\Application\Machine\Command\CreditCommand;
use PHPUnit\Framework\TestCase;

class CreditCommandTest extends TestCase
{
    public function testCanCreateCommand(): void
    {
        $value = 0.65;

        $sut = new CreditCommand($value);

        $this->assertSame($value, $sut->getValue());
    }
}
