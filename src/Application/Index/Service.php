<?php

namespace Crypto\Application\Index;

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
        $queryParams = $this->getQueryParams($input->getCurrency());
        $cryptoCurrencies = $this->client->list($queryParams);
        $result = [];

        foreach ($cryptoCurrencies as $cryptoCurrency) {
            $result[] = $this->buildCrypto($cryptoCurrency);
        }

        if (empty($result)) {
            throw new EmptyResponse(
                'Empty result from the third part service.'
            );
        }

        return new OutputBoundary($result);
    }

    /**
     * @param string $currency
     * @return array<QueryParam>
     */
    private function getQueryParams(string $currency): array
    {
        $queryParams = [];

        $cryptoCurrencies = $this->config
            ->get('crypto.supported_crypto_currencies');

        foreach ($cryptoCurrencies as $cryptoCurrency) {
            $queryParams[] = new QueryParam(
                'crypto-currency',
                $cryptoCurrency
            );
        }

        $queryParams[] = new QueryParam('currency', $currency);

        return $queryParams;
    }
}
