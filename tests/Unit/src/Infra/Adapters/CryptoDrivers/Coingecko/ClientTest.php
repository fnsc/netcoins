<?php

namespace Crypto\Infra\Adapters\CryptoDrivers\Coingecko;

use Crypto\Application\Contracts\Config as configContract;
use Crypto\Domain\ValueObjects\QueryParam;
use GuzzleHttp\Client as HttpClient;
use Mockery as m;
use PHPUnit\Framework\TestCase;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\StreamInterface;

class ClientTest extends TestCase
{
    public function testShouldGetCryptoListDataFromThirdPartAPI(): void
    {
        // Set
        $httpClient = m::mock(HttpClient::class);
        $config = m::mock(configContract::class);
        /** @phpstan-ignore-next-line  */
        $client = new Client($httpClient, $config);

        $response = m::mock(ResponseInterface::class);
        $streamInterface = m::mock(StreamInterface::class);

        $queryParams = [
            new QueryParam('crypto-currency', 'bitcoin'),
            new QueryParam('crypto-currency', 'litecoin'),
            new QueryParam('crypto-currency', 'ethereum'),
            new QueryParam('currency', 'usd'),
        ];

        $options = [
            'headers' => [
                'Content-Type' => 'application/json',
                'Accept' => 'application/json',
            ],
        ];

        $expected = [
            [
                'id' => 'bitcoin',
                'symbol' => 'btc',
                'name' => 'Bitcoin',
                'image' => 'https://assets.coingecko.com/coins/images/1/large/bitcoin.png?1547033579',
                'current_price' => 16961.09,
                'high_24h' => 17149.34,
                'low_24h' => 16927.15,
            ],
        ];

        // Expectations
        /** @phpstan-ignore-next-line  */
        $config->expects()
            ->get('crypto.providers.coingecko.base_url')
            ->andReturn('http://localhost');

        /** @phpstan-ignore-next-line  */
        $config->expects()
            ->get('crypto.providers.coingecko.available_endpoints.list')
            ->andReturn('list/endpoint');

        /** @phpstan-ignore-next-line */
        $config->expects()
            ->get('crypto.providers.coingecko.available_endpoints.price')
            ->andReturn('price/endpoint');

        /** @phpstan-ignore-next-line  */
        $httpClient->expects()
            ->get(
                'http://localhost/list/endpoint?vs_currency=usd&ids=bitcoin%2Clitecoin%2Cethereum',
                $options
            )
            ->andReturn($response);

        /** @phpstan-ignore-next-line  */
        $response->expects()
            ->getBody()
            ->andReturn($streamInterface);

        /** @phpstan-ignore-next-line  */
        $streamInterface->expects()
            ->getContents()
            ->andReturn(json_encode($expected));

        // Action
        $result = $client->list($queryParams);

        // Assertions
        $this->assertSame($expected, $result);
    }

    public function testShouldGetCryptoPriceFromThirdPartAPI(): void
    {
        // Set
        $httpClient = m::mock(HttpClient::class);
        $config = m::mock(configContract::class);
        /** @phpstan-ignore-next-line */
        $client = new Client($httpClient, $config);

        $response = m::mock(ResponseInterface::class);
        $streamInterface = m::mock(StreamInterface::class);

        $queryParams = [
            new QueryParam('crypto-currency', 'bitcoin'),
            new QueryParam('currency', 'usd'),
        ];

        $options = [
            'headers' => [
                'Content-Type' => 'application/json',
                'Accept' => 'application/json',
            ],
        ];

        $expected = [
            [
                'id' => 'bitcoin',
                'symbol' => 'btc',
                'name' => 'Bitcoin',
                'image' => 'https://assets.coingecko.com/coins/images/1/large/bitcoin.png?1547033579',
                'current_price' => 16961.09,
                'high_24h' => 17149.34,
                'low_24h' => 16927.15,
            ],
        ];

        // Expectations
        /** @phpstan-ignore-next-line */
        $config->expects()
            ->get('crypto.providers.coingecko.base_url')
            ->andReturn('http://localhost');

        /** @phpstan-ignore-next-line */
        $config->expects()
            ->get('crypto.providers.coingecko.available_endpoints.price')
            ->andReturn('price/endpoint');

        /** @phpstan-ignore-next-line */
        $config->expects()
            ->get('crypto.providers.coingecko.available_endpoints.list')
            ->andReturn('list/endpoint');

        /** @phpstan-ignore-next-line */
        $httpClient->expects()
            ->get(
                'http://localhost/price/endpoint?vs_currencies=usd&ids=bitcoin',
                $options
            )
            ->andReturn($response);

        /** @phpstan-ignore-next-line */
        $response->expects()
            ->getBody()
            ->andReturn($streamInterface);

        /** @phpstan-ignore-next-line */
        $streamInterface->expects()
            ->getContents()
            ->andReturn(json_encode($expected));

        // Action
        $result = $client->getPrice($queryParams);

        // Assertions
        $this->assertSame($expected, $result);
    }

    public function testShouldGetCrypto24hPriceRangeDataFromThirdPartAPI(): void
    {
        // Set
        $httpClient = m::mock(HttpClient::class);
        $config = m::mock(configContract::class);
        /** @phpstan-ignore-next-line  */
        $client = new Client($httpClient, $config);

        $response = m::mock(ResponseInterface::class);
        $streamInterface = m::mock(StreamInterface::class);

        $queryParams = [
            new QueryParam('crypto-currency', 'bitcoin'),
            new QueryParam('crypto-currency', 'litecoin'),
            new QueryParam('crypto-currency', 'ethereum'),
            new QueryParam('currency', 'usd'),
        ];

        $options = [
            'headers' => [
                'Content-Type' => 'application/json',
                'Accept' => 'application/json',
            ],
        ];

        $expected = [
            [
                'id' => 'bitcoin',
                'symbol' => 'btc',
                'name' => 'Bitcoin',
                'image' => 'https://assets.coingecko.com/coins/images/1/large/bitcoin.png?1547033579',
                'current_price' => 16961.09,
                'high_24h' => 17149.34,
                'low_24h' => 16927.15,
            ],
        ];

        // Expectations
        /** @phpstan-ignore-next-line  */
        $config->expects()
            ->get('crypto.providers.coingecko.base_url')
            ->andReturn('http://localhost');

        /** @phpstan-ignore-next-line  */
        $config->expects()
            ->get('crypto.providers.coingecko.available_endpoints.list')
            ->andReturn('list/endpoint');

        /** @phpstan-ignore-next-line */
        $config->expects()
            ->get('crypto.providers.coingecko.available_endpoints.price')
            ->andReturn('price/endpoint');

        /** @phpstan-ignore-next-line  */
        $httpClient->expects()
            ->get(
                'http://localhost/list/endpoint?vs_currency=usd&ids=bitcoin%2Clitecoin%2Cethereum',
                $options
            )
            ->andReturn($response);

        /** @phpstan-ignore-next-line  */
        $response->expects()
            ->getBody()
            ->andReturn($streamInterface);

        /** @phpstan-ignore-next-line  */
        $streamInterface->expects()
            ->getContents()
            ->andReturn(json_encode($expected));

        // Action
        $result = $client->getPriceRange($queryParams);

        // Assertions
        $this->assertSame($expected, $result);
    }
}
