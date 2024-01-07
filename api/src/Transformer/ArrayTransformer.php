<?php

namespace App\Transformer;

/**
 * @implements Transformer<iterable, iterable>
 */
readonly class ArrayTransformer extends AbstractTransformer implements Transformer
{
    public function transform($data, array $context = []): iterable
    {
        return array_map(fn(mixed $value) => $this->transformerChain->transform($value), $data);
    }

    public function supports($data): bool
    {
        return is_array($data);
    }
}