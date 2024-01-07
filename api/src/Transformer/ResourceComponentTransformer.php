<?php

namespace App\Transformer;

use App\ApiResource\Component as Input;
use App\Entity\Component as Output;
use App\Entity\Project;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Exception\ORMException;

/**
 * @implements Transformer<Input, Output>
 */
readonly class ResourceComponentTransformer extends AbstractTransformer implements Transformer
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
        $resource->project = $this->entityManager->getReference(Project::class, $data->projectId);

        return $resource;
    }

    public function supports($data): bool
    {
        return $data instanceof Input;
    }
}
