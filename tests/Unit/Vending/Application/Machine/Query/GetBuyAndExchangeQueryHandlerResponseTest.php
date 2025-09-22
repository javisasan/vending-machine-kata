<?php

namespace Tests\Unit\Vending\Application\Machine\Query;

use App\Vending\Application\Machine\Query\GetBuyAndExchangeQueryHandlerResponse;
use App\Vending\Domain\Cash\Entity\Cash;
use App\Vending\Domain\Cash\Entity\CoinCartridge;
use PHPUnit\Framework\TestCase;

class GetBuyAndExchangeQueryHandlerResponseTest extends TestCase
{
    public function testCanCreateHandlerResponse(): void
    {
        $selector = 'water';

        $exchange = new Cash();
        $exchange->append(CoinCartridge::create(0.25, 2));
        $exchange->append(CoinCartridge::create(0.10, 1));

        $sut = new GetBuyAndExchangeQueryHandlerResponse($selector, $exchange);

        $response = $sut->toArray();

        $this->assertSame($selector, $response['item']);
        $this->assertSame(3, count($response['exchange']));
        $this->assertSame(0.25, $response['exchange'][0]);
        $this->assertSame(0.25, $response['exchange'][1]);
        $this->assertSame(0.10, $response['exchange'][2]);
    }
}
