<?php

namespace Tests\Integration\Crypto\Presenters\Http\Controllers\Api;

use Crypto\Application\Contracts\Client;
use Crypto\Application\Exceptions\EmptyResponse;
use Crypto\Application\PriceRange\InputBoundary;
use Crypto\Application\PriceRange\Service;
use Crypto\Domain\ValueObjects\QueryParam;
use Exception;
use GuzzleHttp\Exception\BadResponseException;
use Illuminate\Http\Response;
use Mockery as m;
use Tests\TestCase;
use Throwable;

class PriceRangeControllerTest extends TestCase
{
    public function testShouldReceiveTheCryptoCurrencies(): void
    {
        // Set
        $client = $this->instance(Client::class, $this->createMock(Client::class));
        $queryParams = [
            new QueryParam('currency', 'usd'),
            new QueryParam('crypto-currency', 'bitcoin'),
        ];
        $clientResponse = [
            [
                'id' => 'bitcoin',
                'name' => 'Bitcoin',
                'image' => 'https://assets.coingecko.com/coins/images/1/large/bitcoin.png?1547033579',
                'current_price' => 16961.09,
                'high_24h' => 17149.34,
                'low_24h' => 16927.15,
            ],
        ];

        $expected = [
            'data' => [
                'id' => 'bitcoin',
                'high24' => '17149.34',
                'low24' => '16927.15',
            ],
        ];

        // Expectations
        /** @phpstan-ignore-next-line  */
        $client->expects($this->once())
            ->method('getPriceRange')
            ->with($queryParams)
            ->willReturn($clientResponse);

        // Action
        $result = $this->get(
            'api/v1/crypto/24h-price-range?crypto-currency=bitcoin'
        );

        // Assertions
        $result->assertStatus(Response::HTTP_OK);
        $this->assertSame($expected, $result->getOriginalContent());
    }

    public function testShouldReturnUnprocessableEntityWhenQueryParamNotSent(): void
    {
        // Set
        $expected = [
            'data' => [],
            'errors' => [
                'crypto-currency' => [
                    'The crypto-currency field is required.',
                ],
            ],
        ];

        // Action
        $result = $this->get('api/v1/crypto/24h-price-range');

        // Assertions
        $result->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY);
        $this->assertSame($expected, $result->getOriginalContent());
    }

    /**
     * @dataProvider getExceptionScenarios
     * @param Throwable            $exception
     * @param array<string, mixed> $expected
     * @param int                  $expectedStatusCode
     */
    public function testShouldReturnAnError(Throwable $exception, array $expected, int $expectedStatusCode): void
    {
        // Set
        $service = $this->instance(
            Service::class,
            $this->createMock(Service::class)
        );
        $input = new InputBoundary('bitcoin');

        // Expectations
        /** @phpstan-ignore-next-line  */
        $service->expects($this->once())
            ->method('handle')
            ->with($input)
            ->willThrowException($exception);

        // Action
        $result = $this->get(
            'api/v1/crypto/24h-price-range?crypto-currency=bitcoin'
        );

        // Assertions
        $result->assertStatus($expectedStatusCode);
        $this->assertSame($expected, $result->getOriginalContent());
    }

    /**
     * @return array<string, mixed>
     */
    public function getExceptionScenarios(): array
    {
        return [
            'guzzle exception' => [
                'exception' => m::mock(BadResponseException::class),
                'expected' => [
                    'data' => [],
                    'errors' => 'Error while receiving data from the third part API. Try again later.',
                ],
                'expectedStatusCode' => Response::HTTP_BAD_REQUEST,
            ],
            'empty response exception' => [
                'exception' => m::mock(EmptyResponse::class),
                'expected' => [
                    'data' => [],
                    'errors' => 'Empty return from the third part API. Try again later.',
                ],
                'expectedStatusCode' => Response::HTTP_BAD_REQUEST,
            ],
            'unexpected exception' => [
                'exception' => new Exception('Something unexpected.'),
                'expected' => [
                    'data' => [],
                    'errors' => 'Something unexpected has happened. Try again later.',
                ],
                'expectedStatusCode' => Response::HTTP_INTERNAL_SERVER_ERROR,
            ],
        ];
    }
}
