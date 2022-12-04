<?php

namespace Crypto\Application\Price;

class InputBoundary
{
    private string $cryptoCurrency;

    public function __construct(string $cryptoCurrency)
    {
        $this->cryptoCurrency = $cryptoCurrency;
    }

    public function getCryptoCurrency(): string
    {
        return $this->cryptoCurrency;
    }
}
