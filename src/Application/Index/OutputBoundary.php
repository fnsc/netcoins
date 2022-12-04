<?php

namespace Crypto\Application\Index;

use Crypto\Domain\Entities\Crypto;

class OutputBoundary
{
    /**
     * @var array<int, Crypto>
     */
    private array $cryptoCurrencies;

    /**
     * @param array<int, Crypto> $cryptoCurrencies
     */
    public function __construct(array $cryptoCurrencies)
    {
        $this->cryptoCurrencies = $cryptoCurrencies;
    }

    /**
     * @return array<int, Crypto>
     */
    public function getCryptoCurrencies(): array
    {
        return $this->cryptoCurrencies;
    }
}
