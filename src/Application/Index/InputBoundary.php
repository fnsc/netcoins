<?php

namespace Crypto\Application\Index;

class InputBoundary
{
    private string $currency;

    public function __construct(string $currency)
    {
        $this->currency = $currency;
    }

    public function getCurrency(): string
    {
        return $this->currency;
    }
}
