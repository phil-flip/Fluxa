<?php

namespace App\Transformer;

use App\ApiResource\Cycle as Input;
use App\Entity\Cycle as Output;
use App\Entity\Team;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Exception\ORMException;

/**
 * @implements Transformer<Input, Output>
 */
readonly class ResourceCycleTransformer extends AbstractTransformer implements Transformer
{
    public function __construct(private EntityManagerInterface $entityManager)
    {
    }

    /**
     * @throws ORMException
     */
    public function transform($data, array $context = []): Output
    {
        $resource = new Output();
        $resource->name = $data->name;
        $resource->team = $this->entityManager->getReference(Team::class, $data->teamId);

        return $resource;
    }

    public function supports($data): bool
    {
        return $data instanceof Input;
    }
}
