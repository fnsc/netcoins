<?php

namespace Crypto\Infra\Providers;

use Crypto\Application\Contracts\Client as ClientContract;
use Crypto\Application\Contracts\Config;
use Crypto\Infra\Adapters\Client;
use GuzzleHttp\Client as HttpClient;
use Illuminate\Contracts\Support\DeferrableProvider;
use Illuminate\Support\ServiceProvider;

class ClientServiceProvider extends ServiceProvider implements DeferrableProvider
{
    public function register(): void
    {
        $this->app->bind(ClientContract::class, function () {
            $httpClient = new HttpClient();
            $config = app(Config::class);

            return new Client($httpClient, $config);
        });
    }

    /**
     * @return array<int, string>
     */
    public function provides(): array
    {
        return [ClientContract::class];
    }
}
