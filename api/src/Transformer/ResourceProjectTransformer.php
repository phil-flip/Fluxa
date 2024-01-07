<?php

namespace App\Transformer;

use App\ApiResource\Project as Input;
use App\Entity\Project as Output;

/**
 * @implements Transformer<Input, Output>
 */
readonly class ResourceProjectTransformer extends AbstractTransformer implements Transformer
{
    public function transform($data, array $context = []): Output
    {
        $resource = new Output();
        $resource->name = $data->name;

        return $resource;
    }

    public function supports($data): bool
    {
        return $data instanceof Input;
    }
}
