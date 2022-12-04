<?php

namespace Crypto\Presenters\Transformers;

use Crypto\Domain\Entities\Crypto as CryptoEntity;

class Crypto
{
    /**
     * @param CryptoEntity $crypto
     * @return array<string, string>
     */
    public function transform(CryptoEntity $crypto): array
    {
        return [
            'id' => $crypto->getId(),
            'name' => $crypto->getName(),
            'image' => $crypto->getImage(),
            'currentPrice' => $crypto->getCurrentPrice(),
            'high24' => $crypto->getHigh24(),
            'low24' => $crypto->getLow24(),
        ];
    }
}
