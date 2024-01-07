<?php

namespace App\Transformer;

use App\ApiResource\User as Output;
use App\Entity\User as Input;

/**
 * @implements Transformer<Input, Output>
 */
readonly class EntityUserTransformer extends AbstractTransformer implements Transformer
{
    public function transform($data, array $context = []): Output
    {
        $resource = new Output();
        $resource->id = $data->getId();
        $resource->name = $data->name;
        $resource->photoUrl = $data->profilePhotoUrl;

        return $resource;
    }

    public function supports($data): bool
    {
        return $data instanceof Input;
    }
}