<?php

namespace Crypto\Application;

use Crypto\Domain\Entities\Crypto;

abstract class AbstractService
{
    /**
     * @param array<string, mixed> $cryptoCurrencies
     * @return Crypto
     */
    protected function buildCrypto(array $cryptoCurrencies): Crypto
    {
        return new Crypto(
            $cryptoCurrencies['id'],
            $cryptoCurrencies['name'],
            $cryptoCurrencies['image'],
            $cryptoCurrencies['current_price'],
            $cryptoCurrencies['high_24h'],
            $cryptoCurrencies['low_24h']
        );
    }
}
