<?php

namespace Crypto\Domain\Entities;

use PHPUnit\Framework\TestCase;

class PriceTest extends TestCase
{
    public function testShouldGetPriceInstance(): void
    {
        // Action
        $result = new Price(
            'bitcoin',
            'usd',
            '17123.98'
        );

        // Assertions
        $this->assertSame('bitcoin', $result->getId());
        $this->assertSame('usd', $result->getCurrency());
        $this->assertSame('17123.98', $result->getValue());
    }
}
