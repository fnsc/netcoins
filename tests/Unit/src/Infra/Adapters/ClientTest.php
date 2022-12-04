<?php

namespace Crypto\Infra\Adapters;

use Crypto\Application\Contracts\Config as configContract;
use GuzzleHttp\Client as HttpClient;
use Mockery as m;
use PHPUnit\Framework\TestCase;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\StreamInterface;

class ClientTest extends TestCase
{
    public function testShouldGetDataFromThirdPartAPI(): void
    {
        // Set
        $httpClient = m::mock(HttpClient::class);
        $config = m::mock(configContract::class);
        /** @phpstan-ignore-next-line  */
        $client = new Client($httpClient, $config);

        $response = m::mock(ResponseInterface::class);
        $streamInterface = m::mock(StreamInterface::class);

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
        $httpClient->expects()
            ->get('http://localhost/some/random/endpoint?', $options)
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
        $result = $client->get('some/random/endpoint', []);

        // Assertions
        $this->assertSame($expected, $result);
    }
}
