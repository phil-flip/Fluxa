<?php

namespace App\Transformer;

use App\ApiResource\Team as Input;
use App\Entity\Team as Output;

//use App\Entity\Workspace;

/**
 * @implements Transformer<Input, Output>
 */
readonly class ResourceTeamTransformer extends AbstractTransformer implements Transformer
{
//    public function __construct(private EntityManagerInterface $entityManager)
//    {
//    }

    public function transform($data, array $context = []): Output
    {
        $resource = new Output();
        $resource->name = $data->name;
//        Enable when we support custom workspaces
//        $resource->workspace = $this->entityManager->getReference(Workspace::class, $data->workspaceId);

        return $resource;
    }

    public function supports($data): bool
    {
        return $data instanceof Input;
    }
}
