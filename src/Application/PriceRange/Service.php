<?php

namespace Crypto\Application\PriceRange;

use Crypto\Application\AbstractService;
use Crypto\Application\Contracts\Client;
use Crypto\Application\Contracts\Config;
use Crypto\Application\Exceptions\EmptyResponse;
use Crypto\Domain\ValueObjects\QueryParam;

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
        $queryParams = $this->getQueryParams($input->getCryptoCurrency());
        $cryptoCurrencies = $this->client->list($queryParams);
        $result = null;

        foreach ($cryptoCurrencies as $cryptoCurrency) {
            $result = $this->buildCrypto($cryptoCurrency);
        }

        if (empty($result)) {
            throw new EmptyResponse(
                'Empty result from the third part service.'
            );
        }

        return new OutputBoundary($result);
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

        $result[] = new QueryParam('currency', $currency);
        $result[] = new QueryParam('crypto-currency', $cryptoCurrency);

        return $result;
    }
}
