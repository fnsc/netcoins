<?php

namespace Crypto\Application\Contracts;

use Crypto\Domain\ValueObjects\QueryParam;

interface Client
{
    /**
     * @param array<QueryParam> $queryParams
     * @return array<string, mixed>
     */
    public function list(array $queryParams): array;

    /**
     * @param array<QueryParam> $queryParams
     * @return array<string, mixed>
     */
    public function getPrice(array $queryParams): array;
}
