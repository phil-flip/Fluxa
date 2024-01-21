<?php

namespace App\Transformer;

use App\ApiResource\Workspace as Output;
use App\Entity\Workspace as Input;

/**
 * @implements Transformer<Input, Output>
 */
readonly class EntityWorkspaceTransformer extends AbstractTransformer implements Transformer
{
    public function transform($data, array $context = []): Output
    {
        $resource = new Output();
        $resource->id = $data->getId();
        $resource->slug = $data->slug;

        return $resource;
    }

    public function supports($data): bool
    {
        return $data instanceof Input;
    }
}
