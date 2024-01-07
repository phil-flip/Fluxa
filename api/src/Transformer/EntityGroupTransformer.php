<?php

namespace App\Transformer;

use App\ApiResource\Group;
use App\Entity\Group as Input;

/**
 * @implements Transformer<Input, Group>
 */
readonly class EntityGroupTransformer extends AbstractTransformer implements Transformer
{
    public function transform($data, array $context = []): Group
    {
        $resource = new Group();
        $resource->id = $data->getId();
        $resource->name = $data->name;
        $resource->projectId = $data->project ? $data->project->getId() : null;
        $resource->teamId = $data->team ? $data->team->getId() : null;

        return $resource;
    }

    public function supports($data): bool
    {
        return $data instanceof Input;
    }
}
