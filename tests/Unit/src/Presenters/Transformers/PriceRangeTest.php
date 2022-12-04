<?php

namespace Crypto\Presenters\Transformers;

use Crypto\Domain\Entities\Crypto as CryptoEntity;
use PHPUnit\Framework\TestCase;

class PriceRangeTest extends TestCase
{
    public function testShouldTransform(): void
    {
        // Set
        $transformer = new PriceRange();
        $cryptoEntity = new CryptoEntity(
            'bitcoin',
            'Bitcoin',
            'https://assets.coingecko.com/coins/images/1/large/bitcoin.png?1547033579',
            '16961.09',
            '17149.34',
            '16927.15',
        );

        $expected = [
            'id' => 'bitcoin',
            'high24' => '17149.34',
            'low24' => '16927.15',
        ];

        // Action
        $result = $transformer->transform($cryptoEntity);

        // Assertions
        $this->assertSame($expected, $result);
    }
}
