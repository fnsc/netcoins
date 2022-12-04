<?php

namespace Crypto\Domain\Entities;

use PHPUnit\Framework\TestCase;

class CryptoTest extends TestCase
{
    public function testShouldGetCryptoInstance(): void
    {
        // Action
        $result = new Crypto(
            'bitcoin',
            'Bitcoin',
            'https://assets.coingecko.com/coins/images/1/large/bitcoin.png?1547033579',
            '16961.09',
            '17149.34',
            '16927.15',
        );

        // Assertions
        $this->assertSame('bitcoin', $result->getId());
        $this->assertSame('Bitcoin', $result->getName());
        $this->assertSame(
            'https://assets.coingecko.com/coins/images/1/large/bitcoin.png?1547033579',
            $result->getImage()
        );
        $this->assertSame('16961.09', $result->getCurrentPrice());
        $this->assertSame('17149.34', $result->getHigh24());
        $this->assertSame('16927.15', $result->getLow24());
    }
}
