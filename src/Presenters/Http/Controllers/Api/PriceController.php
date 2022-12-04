<?php

namespace Crypto\Presenters\Http\Controllers\Api;

use Crypto\Application\Contracts\Config;
use Crypto\Application\Price\InputBoundary;
use Crypto\Application\Price\Service;
use Crypto\Presenters\Http\Controllers\AbstractController;
use Crypto\Presenters\Transformers\Price;
use Exception;
use GuzzleHttp\Exception\GuzzleException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;
use Psr\Log\LoggerInterface;

class PriceController extends AbstractController
{
    private Service $service;
    private Price $transformer;
    private LoggerInterface $logger;
    private Config $config;

    public function __construct(
        Service $service,
        Price $transformer,
        Config $config,
        LoggerInterface $logger
    ) {
        $this->service = $service;
        $this->transformer = $transformer;
        $this->config = $config;
        $this->logger = $logger;
    }

    public function price(Request $request): JsonResponse
    {
        try {
            $request->validate($this->getRules());
            $input = new InputBoundary($request->get('crypto-currency'));
            $output = $this->service->handle($input);
            $result = $this->transformer->transform($output->getPrice());

            return new JsonResponse([
                'data' => $result,
            ], Response::HTTP_OK);
        } catch (ValidationException $exception) {
            $this->logger->info(
                '[Crypto|Price] Error validating the form request',
                [
                    'exception' => $exception,
                    'message' => $exception->getMessage(),
                ]
            );

            return new JsonResponse([
                'data' => [],
                'errors' => $this->getErrors($exception),
            ], Response::HTTP_UNPROCESSABLE_ENTITY);
        } catch (GuzzleException $exception) {
            $this->logger->error('[Crypto|Price] Error while receiving data from the third part API.', compact('exception'));

            return new JsonResponse([
                'data' => [],
                'errors' => 'Error while receiving data from the third part API. Try again later.',
            ], Response::HTTP_BAD_REQUEST);
        } catch (Exception $exception) {
            $this->logger->error('[Crypto|Price] Something unexpected has happened.', compact('exception'));

            return new JsonResponse([
                'data' => [],
                'errors' => 'Something unexpected has happened. Try again later.',
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * @return array<string,mixed>
     */
    private function getRules(): array
    {
        return [
            'crypto-currency' => [
                'required',
                'string',
                Rule::in($this->config->get('crypto.supported_crypto_currencies')),
            ],
        ];
    }
}
