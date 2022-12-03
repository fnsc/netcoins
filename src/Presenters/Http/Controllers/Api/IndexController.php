<?php

namespace Crypto\Presenters\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Crypto\Application\Contracts\Config;
use Crypto\Application\Index\InputBoundary;
use Crypto\Application\Index\Service;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;
use Psr\Log\LoggerInterface;

class IndexController extends Controller
{
    private Service $service;
    private LoggerInterface $logger;
    private Config $config;

    public function __construct(
        Service $service,
        Config $config,
        LoggerInterface $logger
    ) {
        $this->service = $service;
        $this->config = $config;
        $this->logger = $logger;
    }

    public function index(Request $request): JsonResponse
    {
        try {
            $request->validate($this->getRules());
            $input = new InputBoundary($request->get('currency'));

            $result = $this->service->handle($input);

            return new JsonResponse([], Response::HTTP_OK);
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
        } catch (Exception $exception) {
            $this->logger->error('', compact('exception'));

            return new JsonResponse([], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * @return array<string,mixed>
     */
    private function getErrors(ValidationException $exception): array
    {
        return $exception->validator
            ->errors()
            ->jsonSerialize();
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
