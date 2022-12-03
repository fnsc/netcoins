<?php

namespace Crypto\Application\Contracts;

interface Config
{
    /**
     * @phpstan-ignore-next-line
     */
    public function get(string $config);
}
