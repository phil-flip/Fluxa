<?php

namespace App\Transformer;

use App\ApiResource\Workspace as Input;
use App\Entity\Workspace as Output;

/**
 * @implements Transformer<Input, Output>
 */
readonly class ResourceWorkspaceTransformer extends AbstractTransformer implements Transformer
{
    public function transform($data, array $context = []): Output
    {
        $resource = new Output();
        $resource->slug = $data->slug;

        return $resource;
    }

    public function supports($data): bool
    {
        return $data instanceof Input;
    }
}
