<?php

namespace Crypto\Presenters\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Validation\ValidationException;

abstract class AbstractController extends Controller
{
    /**
     * @return array<string,mixed>
     */
    protected function getErrors(ValidationException $exception): array
    {
        return $exception->validator
            ->errors()
            ->jsonSerialize();
    }
}
