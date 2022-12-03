<?php

namespace Crypto\Application\Index;

use Crypto\Application\Contracts\Client;
use Crypto\Application\Contracts\Config;
use Crypto\Domain\Entities\Crypto;

class Service
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
        $queryParams = $this->getQueryParams($input->getCurrency());
        $cryptoCurrencies = $this->client->get($endpoint, $queryParams);
        $result = [];

        foreach ($cryptoCurrencies as $cryptoCurrency) {
            $result[] = $this->buildCrypto($cryptoCurrency);
        }

        return new OutputBoundary($result);
    }

    /**
     * @param string $currency
     * @return array<string, string>
     */
    private function getQueryParams(string $currency): array
    {
        $cryptoCurrencies = $this->config->get(
            'crypto.supported_crypto_currencies'
        );
        $ids = '';

        foreach ($cryptoCurrencies as $cryptoCurrency) {
            $ids .= $cryptoCurrency . ',';
        }

        return [
            'ids' => rtrim($ids, ','),
            'vs_currency' => $currency,
        ];
    }

    /**
     * @param array<string, mixed> $cryptoCurrencies
     * @return Crypto
     */
    private function buildCrypto(array $cryptoCurrencies): Crypto
    {
        return new Crypto(
            $cryptoCurrencies['id'],
            $cryptoCurrencies['name'],
            $cryptoCurrencies['image'],
            $cryptoCurrencies['current_price'],
            $cryptoCurrencies['high_24h'],
            $cryptoCurrencies['low_24h']
        );
    }
}
