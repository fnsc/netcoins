<?php

namespace Crypto\Infra\Adapters\CryptoDrivers\Coingecko;

use Crypto\Application\Contracts\Client as ClientContract;
use Crypto\Application\Contracts\Config as ConfigContract;
use Crypto\Domain\ValueObjects\QueryParam;
use GuzzleHttp\Client as HttpClient;
use GuzzleHttp\Exception\GuzzleException;

class Client implements ClientContract
{
    private HttpClient $client;
    private ConfigContract $config;

    public function __construct(HttpClient $client, ConfigContract $config)
    {
        $this->client = $client;
        $this->config = $config;
    }

    /**
     * @param array<QueryParam> $queryParams
     * @return array<string, mixed>
     * @throws GuzzleException
     */
    public function list(array $queryParams = []): array
    {
        $endpoint = $this->config
            ->get('crypto.providers.coingecko.available_endpoints.list');
        $url = $this->buildUrl($endpoint, $queryParams);
        $options = $this->getOptions();
        $response = $this->client->get($url, $options);
        $content = $response->getBody()->getContents();

        return json_decode($content, true);
    }

    /**
     * @param array<QueryParam> $queryParams
     * @return array<string, mixed>
     * @throws GuzzleException
     */
    public function getPrice(array $queryParams = []): array
    {
        $endpoint = $this->config
            ->get('crypto.providers.coingecko.available_endpoints.price');
        $url = $this->buildUrl($endpoint, $queryParams);
        $options = $this->getOptions();
        $response = $this->client->get($url, $options);
        $content = $response->getBody()->getContents();

        return json_decode($content, true);
    }

    /**
     * @param array<QueryParam> $queryParams
     * @return array<string, mixed>
     * @throws GuzzleException
     */
    public function getPriceRange(array $queryParams): array
    {
        return $this->list($queryParams);
    }

    /**
     * @return array<string, mixed>
     */
    private function getOptions(): array
    {
        $options['headers'] = $this->getHeaders();

        return $options;
    }

    /**
     * @return array<string, string>
     */
    private function getHeaders(): array
    {
        return [
            'Content-Type' => 'application/json',
            'Accept' => 'application/json',
        ];
    }

    /**
     * @param string            $endpoint
     * @param array<QueryParam> $queryParams
     * @return string
     */
    private function buildUrl(string $endpoint, array $queryParams): string
    {
        $baseUrl = rtrim(
            $this->config->get('crypto.providers.coingecko.base_url'),
            '/'
        );

        $queryParams = $this->buildQueryParams($queryParams, $endpoint);

        return $baseUrl . '/' . $endpoint . '?' . $queryParams;
    }

    /**
     * @param array<QueryParam> $queryParams
     */
    private function buildQueryParams(array $queryParams, string $endpoint): string
    {
        if (empty($queryParams)) {
            return '';
        }

        $result = [
            'vs_currency' => '',
            'vs_currencies' => '',
            'ids' => '',
        ];

        foreach ($queryParams as $queryParam) {
            if ('crypto-currency' === $queryParam->getType()) {
                $result['ids'] .= $queryParam->getValue() . ',';

                continue;
            }

            $result['vs_currency'] .= $queryParam->getValue() . ',';
            $result['vs_currencies'] .= $queryParam->getValue() . ',';
        }

        $result['vs_currency'] = rtrim($result['vs_currency'], ',');
        $result['vs_currencies'] = rtrim($result['vs_currencies'], ',');
        $result['ids'] = rtrim($result['ids'], ',');

        $listEndpoint = $this->config
            ->get('crypto.providers.coingecko.available_endpoints.list');

        $priceEndpoint = $this->config
            ->get('crypto.providers.coingecko.available_endpoints.price');

        if ($endpoint === $listEndpoint) {
            unset($result['vs_currencies']);
        }

        if ($endpoint === $priceEndpoint) {
            unset($result['vs_currency']);
        }

        return http_build_query($result);
    }
}
