<?php

namespace Crypto\Application\Index;

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
}
