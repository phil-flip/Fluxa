<?php

namespace App\Transformer;

use App\ApiResource\Workflow as Output;
use App\Entity\Workflow as Input;

/**
 * @implements Transformer<Input, Output>
 */
readonly class EntityWorkflowTransformer extends AbstractTransformer implements Transformer
{
    public function transform($data, array $context = []): Output
    {
        $resource = new Output();
        $resource->id = $data->getId();
        $resource->statuses = $this->transformerChain->transform($data->statuses->toArray());

        return $resource;
    }

    public function supports($data): bool
    {
        return $data instanceof Input;
    }
}