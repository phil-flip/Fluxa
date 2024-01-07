<?php

namespace App\Transformer;

use App\ApiResource\Component as Output;
use App\Entity\Component as Input;

/**
 * @implements Transformer<Input, Output>
 */
readonly class EntityComponentTransformer extends AbstractTransformer implements Transformer
{
    public function transform($data, array $context = []): Output
    {
        $resource = new Output();
        $resource->id = $data->getId();
        $resource->name = $data->name;
        $resource->projectId = $data->project->getId();

        return $resource;
    }

    public function supports($data): bool
    {
        return $data instanceof Input;
    }
}