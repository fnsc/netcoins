<?php

namespace Crypto\Presenters\Transformers;

use Crypto\Domain\Entities\Price as PriceEntity;

class Price
{
    /**
     * @param PriceEntity $price
     * @return array<string, string>
     */
    public function transform(PriceEntity $price): array
    {
        return [
            'id' => $price->getId(),
            'currency' => $price->getCurrency(),
            'value' => $price->getValue(),
        ];
    }
}
