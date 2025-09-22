<?php

namespace Tests\Unit\Vending\Application\Machine\Query;

use App\Vending\Application\Machine\Query\GetBuyAndExchangeQuery;
use PHPUnit\Framework\TestCase;

class GetBuyAndExchangeQueryTest extends TestCase
{
    public function testCanCreateQuery(): void
    {
        $selector = 'water';

        $sut = new GetBuyAndExchangeQuery($selector);

        $this->assertSame($selector, $sut->getSelector());
    }
}
