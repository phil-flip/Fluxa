<?php

namespace App\Transformer;

use App\ApiResource\Label as LabelResource;
use App\Entity\Label as LabelEntity;

/**
 * @implements Transformer<LabelEntity, LabelResource>
 */
readonly class EntityLabelTransformer extends AbstractTransformer implements Transformer
{
    public function transform($data, array $context = []): LabelResource
    {
        $resource = new LabelResource();
        $resource->id = $data->getId();
        $resource->name = $data->name;
        $resource->properties = $data->properties;
        $resource->projectId = $data->project ? $data->project->getId() : null;
        $resource->teamId = $data->team ? $data->team->getId() : null;

        return $resource;
    }

    public function supports($data): bool
    {
        return $data instanceof LabelEntity;
    }
}