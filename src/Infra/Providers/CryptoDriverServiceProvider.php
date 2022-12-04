<?php

namespace Crypto\Infra\Providers;

use Crypto\Application\Contracts\Client as ClientContract;
use Crypto\Application\Contracts\Config;
use Crypto\Infra\Adapters\CryptoDrivers\Coingecko\Client;
use GuzzleHttp\Client as HttpClient;
use Illuminate\Contracts\Support\DeferrableProvider;
use Illuminate\Support\ServiceProvider;

class CryptoDriverServiceProvider extends ServiceProvider implements DeferrableProvider
{
    private const CRYPTO_DRIVER_COINGECKO = 'coingecko';

    public function register(): void
    {
        $cryptoDriver = config('crypto.driver');

        if (self::CRYPTO_DRIVER_COINGECKO === $cryptoDriver) {
            $this->app->bind(ClientContract::class, function () {
                $httpClient = new HttpClient();
                $config = app(Config::class);

                return new Client($httpClient, $config);
            });

            return;
        }
    }

    /**
     * @return array<int, string>
     */
    public function provides(): array
    {
        return [ClientContract::class];
    }
}
