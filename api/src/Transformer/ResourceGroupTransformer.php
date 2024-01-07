<?php

namespace App\Transformer;

use App\ApiResource\Group as Input;
use App\Entity\Team;
use App\Entity\Group as Output;
use App\Entity\Project;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Exception\ORMException;

/**
 * @implements Transformer<Input, Output>
 */
readonly class ResourceGroupTransformer extends AbstractTransformer implements Transformer
{
    public function __construct(private EntityManagerInterface $entityManager)
    {
    }

    /**
     * @inheritDoc
     * @throws ORMException
     */
    public function transform($data, array $context = []): Output
    {
        $resource = new Output();
        $resource->name = $data->name;
        $resource->project = $data->projectId ? $this->entityManager->getReference(
            Project::class,
            $data->projectId
        ) : null;
        $resource->team = $data->teamId ? $this->entityManager->getReference(
            Team::class,
            $data->teamId
        ) : null;

        return $resource;
    }

    public function supports($data): bool
    {
        return $data instanceof Input;
    }
}
