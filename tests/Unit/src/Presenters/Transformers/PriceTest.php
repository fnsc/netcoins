<?php

namespace Crypto\Presenters\Transformers;

use Crypto\Domain\Entities\Price as PriceEntity;
use PHPUnit\Framework\TestCase;

class PriceTest extends TestCase
{
    public function testShouldTransform(): void
    {
        // Set
        $transformer = new Price();
        $price = new PriceEntity(
            'bitcoin',
            'usd',
            '17049.21'
        );

        $expected = [
            'id' => 'bitcoin',
            'currency' => 'usd',
            'value' => '17049.21',
        ];

        // Action
        $result = $transformer->transform($price);

        // Assertions
        $this->assertSame($expected, $result);
    }
}
