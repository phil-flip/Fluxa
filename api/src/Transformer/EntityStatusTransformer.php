<?php

namespace App\Transformer;

use App\ApiResource\Status as StatusResource;
use App\Entity\Status as StatusEntity;

/**
 * @implements Transformer<StatusEntity, StatusResource>
 */
readonly class EntityStatusTransformer extends AbstractTransformer implements Transformer
{
    public function transform($data, array $context = []): StatusResource
    {
        $resource = new StatusResource();
        $resource->id = $data->getId();
        $resource->name = $data->name;
        $resource->state = $data->state->value;
        $resource->sortOrder = $data->state->value;

        return $resource;
    }

    public function supports($data): bool
    {
        return $data instanceof StatusEntity;
    }
}
