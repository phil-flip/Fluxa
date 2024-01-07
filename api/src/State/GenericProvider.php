<?php

namespace App\State;

use ApiPlatform\Metadata\Operation;
use ApiPlatform\State\ProviderInterface;
use App\Entity\Component;
use App\Entity\Cycle;
use App\Entity\Group;
use App\Entity\Label;
use App\Entity\Milestone;
use App\Entity\Project;
use App\Entity\Status;
use App\Entity\Team;
use App\Entity\Workflow;
use App\Transformer\TransformerChain;
use Doctrine\ORM\EntityManagerInterface;
use LogicException;

readonly class GenericProvider implements ProviderInterface
{
    public function __construct(private EntityManagerInterface $entityManager, private TransformerChain $transformer)
    {
    }

    public function provide(Operation $operation, array $uriVariables = [], array $context = []): object|array|null
    {
        switch ($operation->getClass()) {
            case \App\ApiResource\Component::class:
                $results = $this->entityManager->getRepository(Component::class)->findAll();
                break;
            case \App\ApiResource\Cycle::class:
                $results = $this->entityManager->getRepository(Cycle::class)->findAll();
                break;
            case \App\ApiResource\Group::class:
                $results = $this->entityManager->getRepository(Group::class)->findAll();
                break;
            case \App\ApiResource\Label::class:
                $results = $this->entityManager->getRepository(Label::class)->findAll();
                break;
            case \App\ApiResource\Milestone::class:
                $results = $this->entityManager->getRepository(Milestone::class)->findAll();
                break;
            case \App\ApiResource\Project::class:
                $results = $this->entityManager->getRepository(Project::class)->findAll();
                break;
            case \App\ApiResource\Status::class:
                $results = $this->entityManager->getRepository(Status::class)->findAll();
                break;
            case \App\ApiResource\Team::class:
                $results = $this->entityManager->getRepository(Team::class)->findAll();
                break;
            case \App\ApiResource\Workflow::class:
                $results = $this->entityManager->getRepository(Workflow::class)->findAll();
                break;
            default:
                throw new LogicException(sprintf('Unknown operation class "%s"', $operation->getClass()));
        }

        return $this->transformer->transform($results);
    }
}
