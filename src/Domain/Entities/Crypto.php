<?php

namespace Crypto\Domain\Entities;

class Crypto
{
    private string $id;
    private string $name;
    private string $image;
    private string $currentPrice;
    private string $high24;
    private string $low24;

    public function __construct(
        string $id,
        string $name,
        string $image,
        string $currentPrice,
        string $high24,
        string $low24
    ) {
        $this->id = $id;
        $this->name = $name;
        $this->image = $image;
        $this->currentPrice = $currentPrice;
        $this->high24 = $high24;
        $this->low24 = $low24;
    }

    public function getId(): string
    {
        return $this->id;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getImage(): string
    {
        return $this->image;
    }

    public function getCurrentPrice(): string
    {
        return $this->currentPrice;
    }

    public function getHigh24(): string
    {
        return $this->high24;
    }

    public function getLow24(): string
    {
        return $this->low24;
    }
}
