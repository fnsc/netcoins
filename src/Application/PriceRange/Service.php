<?php

namespace Crypto\Application\PriceRange;

use Crypto\Application\AbstractService;
use Crypto\Application\Contracts\Client;
use Crypto\Application\Contracts\Config;

class Service extends AbstractService
{
    private Client $client;
    private Config $config;

    public function __construct(Client $client, Config $config)
    {
        $this->client = $client;
        $this->config = $config;
    }

    public function handle(InputBoundary $input): OutputBoundary
    {
        $endpoint = $this->config->get(
            'crypto.providers.coingecko.available_endpoints.list'
        );
        $queryParams = $this->getQueryParams($input->getCryptoCurrency());
        $cryptoCurrencies = $this->client->get($endpoint, $queryParams);
        $result = null;

        foreach ($cryptoCurrencies as $cryptoCurrency) {
            $result = $this->buildCrypto($cryptoCurrency);
        }

        /** @phpstan-ignore-next-line  */
        return new OutputBoundary($result);
    }

    /**
     * @param string $cryptoCurrency
     * @return array<string, string>
     */
    private function getQueryParams(string $cryptoCurrency): array
    {
        $currency = $this->config->get(
            'crypto.available_currencies'
        );

        return [
            'ids' => $cryptoCurrency,
            'vs_currency' => (string) current($currency),
        ];
    }
}
