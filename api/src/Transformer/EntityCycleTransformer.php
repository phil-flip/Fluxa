<?php

namespace App\Transformer;

use App\ApiResource\Cycle as Output;
use App\Entity\Cycle as Input;

/**
 * @implements Transformer<Input, Output>
 */
readonly class EntityCycleTransformer extends AbstractTransformer implements Transformer
{
    public function transform($data, array $context = []): Output
    {
        $resource = new Output();
        $resource->id = $data->getId();
        $resource->name = $data->name;
        $resource->teamId = $data->team->getId();

        return $resource;
    }

    public function supports($data): bool
    {
        return $data instanceof Input;
    }
}