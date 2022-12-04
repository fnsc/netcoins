<?php

namespace Crypto\Presenters\Http\Controllers\Api;

use Crypto\Application\Contracts\Config;
use Crypto\Application\Exceptions\EmptyResponse;
use Crypto\Application\Index\InputBoundary;
use Crypto\Application\Index\Service;
use Crypto\Presenters\Http\Controllers\AbstractController;
use Crypto\Presenters\Transformers\Crypto;
use Exception;
use GuzzleHttp\Exception\GuzzleException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;
use Psr\Log\LoggerInterface;

class IndexController extends AbstractController
{
    private Service $service;
    private Crypto $transformer;
    private LoggerInterface $logger;
    private Config $config;

    public function __construct(
        Service $service,
        Crypto $transformer,
        Config $config,
        LoggerInterface $logger
    ) {
        $this->service = $service;
        $this->transformer = $transformer;
        $this->config = $config;
        $this->logger = $logger;
    }

    public function index(Request $request): JsonResponse
    {
        try {
            $request->validate($this->getRules());
            $input = new InputBoundary($request->get('currency'));
            $output = $this->service->handle($input);
            $result = [];

            foreach ($output->getCryptoCurrencies() as $cryptoCurrency) {
                $result[] = $this->transformer->transform($cryptoCurrency);
            }

            return new JsonResponse([
                'data' => $result,
            ], Response::HTTP_OK);
        } catch (ValidationException $exception) {
            $this->logger->info(
                '[Crypto|Index] Error validating the form request',
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
            $this->logger->error('[Crypto|Index] Error while receiving data from the third part API.', compact('exception'));

            return new JsonResponse([
                'data' => [],
                'errors' => 'Error while receiving data from the third part API. Try again later.',
            ], Response::HTTP_BAD_REQUEST);
        } catch (EmptyResponse $exception) {
            $this->logger->error('[Crypto|Index] Empty return from the third part API.', compact('exception'));

            return new JsonResponse([
                'data' => [],
                'errors' => 'Empty return from the third part API. Try again later.',
            ], Response::HTTP_BAD_REQUEST);
        } catch (Exception $exception) {
            $this->logger->error('[Crypto|Index] Something unexpected has happened.', compact('exception'));

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
            'currency' => [
                'required',
                'string',
                'max:3',
                'min:3',
                Rule::in($this->config->get('crypto.available_currencies')),
            ],
        ];
    }
}
