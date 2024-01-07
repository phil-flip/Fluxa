<?php

namespace App\Filter;

use ApiPlatform\Api\FilterInterface;
use Symfony\Component\DependencyInjection\Attribute\Autoconfigure;

#[Autoconfigure(autowire: false)]
readonly class QueryFilter implements FilterInterface
{
    public function __construct(private array $description)
    {
    }

    public function getDescription(string $resourceClass): array
    {
        return $this->description;
    }
}