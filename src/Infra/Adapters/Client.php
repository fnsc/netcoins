<?php

namespace Crypto\Infra\Adapters;

use Crypto\Application\Contracts\Client as ClientContract;
use Crypto\Application\Contracts\Config as ConfigContract;
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
     * @param string                $endpoint
     * @param array<string, string> $queryParams
     * @return array<string, mixed>
     * @throws GuzzleException
     */
    public function get(string $endpoint, array $queryParams = []): array
    {
        $url = $this->buildUrl($endpoint, $queryParams);
        $options = $this->getOptions();
        $response = $this->client->get($url, $options);
        $content = $response->getBody()->getContents();

        return json_decode($content, true);
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
     * @param string                $endpoint
     * @param array<string, string> $queryParams
     * @return string
     */
    private function buildUrl(string $endpoint, array $queryParams): string
    {
        $baseUrl = rtrim(
            $this->config->get('crypto.providers.coingecko.base_url'),
            '/'
        );
        $queryParams = http_build_query($queryParams);

        return $baseUrl . '/' . $endpoint . '?' . $queryParams;
    }
}
