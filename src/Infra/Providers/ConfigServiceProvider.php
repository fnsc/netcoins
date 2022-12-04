<?php

namespace Crypto\Infra\Providers;

use Crypto\Application\Contracts\Config as ConfigContract;
use Crypto\Infra\Adapters\Config;
use Illuminate\Config\Repository;
use Illuminate\Contracts\Support\DeferrableProvider;
use Illuminate\Support\ServiceProvider;

class ConfigServiceProvider extends ServiceProvider implements DeferrableProvider
{
    public function register(): void
    {
        $this->app->bind(ConfigContract::class, function () {
            $config = app(Repository::class);

            return new Config($config);
        });
    }

    /**
     * @return array<int, string>
     */
    public function provides(): array
    {
        return [ConfigContract::class];
    }
}
