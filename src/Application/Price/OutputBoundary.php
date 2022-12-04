<?php

namespace Crypto\Application\Price;

use Crypto\Domain\Entities\Price;

class OutputBoundary
{
    private Price $price;

    public function __construct(Price $price)
    {
        $this->price = $price;
    }

    public function getPrice(): Price
    {
        return $this->price;
    }
}
