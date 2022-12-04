<?php

namespace Crypto\Infra\Adapters;

use Crypto\Application\Contracts\Config as ConfigContract;
use Illuminate\Config\Repository;

class Config implements ConfigContract
{
    private Repository $config;

    public function __construct(Repository $config)
    {
        $this->config = $config;
    }

    /**
     * @phpstan-ignore-next-line
     */
    public function get(string $config)
    {
        return $this->config->get($config);
    }
}
