<?php

namespace Crypto\Application\Contracts;

interface Client
{
    /**
     * @param string                $endpoint
     * @param array<string, string> $queryParams
     * @return array<string, mixed>
     */
    public function get(string $endpoint, array $queryParams): array;
}
