<?php

namespace Crypto\Application\Index;

use Crypto\Application\Contracts\Client;
use Crypto\Application\Contracts\Config;
use Crypto\Domain\Entities\Crypto;
use Mockery as m;
use PHPUnit\Framework\TestCase;

class ServiceTest extends TestCase
{
    public function testShouldHandle(): void
    {
        // Set
        $client = m::mock(Client::class);
        $config = m::mock(Config::class);
        /** @phpstan-ignore-next-line  */
        $service = new Service($client, $config);

        $input = new InputBoundary('usd');

        // Expectations
        /** @phpstan-ignore-next-line  */
        $config->expects()
            ->get('crypto.providers.coingecko.available_endpoints.list')
            ->andReturn('provider/list/endpoint');

        /** @phpstan-ignore-next-line  */
        $config->expects()
            ->get('crypto.supported_crypto_currencies')
            ->andReturn(['bitcoin']);

        /** @phpstan-ignore-next-line  */
        $client->expects()
            ->get(
                'provider/list/endpoint',
                ['ids' => 'bitcoin', 'vs_currency' => 'usd']
            )
            ->andReturn([
                [
                    'id' => 'bitcoin',
                    'name' => 'Bitcoin',
                    'image' => 'https://assets.coingecko.com/coins/images/1/large/bitcoin.png?1547033579',
                    'current_price' => '16961.09',
                    'high_24h' => '17149.34',
                    'low_24h' => '16927.15',
                ],
            ]);

        // Action
        $result = $service->handle($input);

        // Assertions
        $this->assertInstanceOf(OutputBoundary::class, $result);
        $this->assertInstanceOf(
            Crypto::class,
            current($result->getCryptoCurrencies())
        );
    }
}
