<?php

namespace Crypto\Application\Index;

use Crypto\Application\Contracts\Client;
use Crypto\Application\Contracts\Config;
use Crypto\Domain\Entities\Crypto;
use Crypto\Domain\ValueObjects\QueryParam;
use Mockery as m;
use PHPUnit\Framework\TestCase;

class ServiceTest extends TestCase
{
    public function testShouldHandle(): void
    {
        // Set
        $client = $this->createMock(Client::class);
        $config = m::mock(Config::class);
        /** @phpstan-ignore-next-line  */
        $service = new Service($client, $config);

        $input = new InputBoundary('usd');

        $queryParams = [
            new QueryParam('crypto-currency', 'bitcoin'),
            new QueryParam('currency', 'usd'),
        ];

        // Expectations
        /** @phpstan-ignore-next-line  */
        $config->expects()
            ->get('crypto.providers.coingecko.available_endpoints.list')
            ->andReturn('provider/list/endpoint');

        /** @phpstan-ignore-next-line  */
        $config->expects()
            ->get('crypto.supported_crypto_currencies')
            ->andReturn(['bitcoin']);

        $client->expects($this->once())
            ->method('list')
            ->with($queryParams)
            ->willReturn([
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
