<?php

namespace Crypto\Domain\Entities;

class Price
{
    private string $id;
    private string $currency;
    private string $value;

    public function __construct(string $id, string $currency, string $value)
    {
        $this->id = $id;
        $this->currency = $currency;
        $this->value = $value;
    }

    public function getId(): string
    {
        return $this->id;
    }

    public function getCurrency(): string
    {
        return $this->currency;
    }

    public function getValue(): string
    {
        return $this->value;
    }
}
