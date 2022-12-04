<?php

namespace Crypto\Application\Price;

use Crypto\Application\Contracts\Client;
use Crypto\Application\Contracts\Config;
use Crypto\Domain\Entities\Price;
use Crypto\Domain\ValueObjects\QueryParam;

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
        $queryParams = $this->getQueryParams($input->getCryptoCurrency());
        $cryptoPrices = $this->client->getPrice($queryParams);

        foreach ($cryptoPrices as $key => $cryptoPrice) {
            $price = $this->buildPrice($key, $cryptoPrice);
        }

        /** @phpstan-ignore-next-line  */
        return new OutputBoundary($price);
    }

    /**
     * @param string $cryptoCurrency
     * @return array<QueryParam>
     */
    private function getQueryParams(string $cryptoCurrency): array
    {
        $result = [];
        $currency = current($this->config
            ->get('crypto.available_currencies')) ?? '';

        $result[] = new QueryParam('crypto-currency', $cryptoCurrency);
        $result[] = new QueryParam('currency', $currency);

        return $result;
    }

    /**
     * @param string                $id
     * @param array<string, string> $price
     * @return Price
     */
    private function buildPrice(string $id, array $price): Price
    {
        $currency = (string) array_key_first($price);
        $value = (string) current($price);

        return new Price(
            $id,
            $currency,
            $value
        );
    }
}
