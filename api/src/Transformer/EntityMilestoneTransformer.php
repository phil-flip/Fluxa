<?php

namespace App\Transformer;

use App\ApiResource\Milestone as Output;
use App\Entity\Milestone as Input;

/**
 * @implements Transformer<Input, Output>
 */
readonly class EntityMilestoneTransformer extends AbstractTransformer implements Transformer
{
    public function transform($data, array $context = []): Output
    {
        $resource = new Output();
        $resource->id = $data->getId();
        $resource->sortOrder = $data->sortOrder;
        $resource->name = $data->name;
        $resource->projectId = $data->project->getId();

        return $resource;
    }

    public function supports($data): bool
    {
        return $data instanceof Input;
    }
}