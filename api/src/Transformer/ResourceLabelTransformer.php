<?php

namespace App\Transformer;

use App\ApiResource\Label as Input;
use App\Entity\Label as Output;
use App\Entity\Project;
use App\Entity\Team;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Exception\ORMException;

/**
 * @implements Transformer<Input, Output>
 */
readonly class ResourceLabelTransformer extends AbstractTransformer implements Transformer
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
        $resource->properties = [
            'color' => $this->createRandomColor(),
        ];

        return $resource;
    }

    private function createRandomColor(): string
    {
        $min = 0xCCCCCC;
        $max = 0xFFFFFF;

        return sprintf("#%06x", rand($min, $max));
    }

    public function supports($data): bool
    {
        return $data instanceof Input;
    }
}
