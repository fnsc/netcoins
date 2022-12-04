<?php

namespace Crypto\Application\PriceRange;

use Crypto\Domain\Entities\Crypto;

class OutputBoundary
{
    private Crypto $crypto;

    public function __construct(Crypto $crypto)
    {
        $this->crypto = $crypto;
    }

    public function getCrypto(): Crypto
    {
        return $this->crypto;
    }
}
