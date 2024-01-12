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
use App\Entity\Team;
use App\Entity\User;
use App\Entity\Workspace;
use App\Transformer\TransformerChain;
use Doctrine\ORM\EntityManagerInterface;
use LogicException;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

readonly class GenericProvider implements ProviderInterface
{
    public function __construct(
        private EntityManagerInterface $entityManager,
        private TransformerChain $transformer,
        private TokenStorageInterface $tokenStorage
    ) {
    }

    public function provide(Operation $operation, array $uriVariables = [], array $context = []): object|array|null
    {
        /** @var User $user */
        $user = $this->tokenStorage->getToken()?->getUser();
        $workspaceIds = $user->workspaces->map(fn(Workspace $workspace) => $workspace->getId())->toArray();

        switch ($operation->getClass()) {
            case \App\ApiResource\Component::class:
                $results = $this->entityManager->getRepository(Component::class)->findForWorkspaces($workspaceIds);
                break;
            case \App\ApiResource\Cycle::class:
                $results = $this->entityManager->getRepository(Cycle::class)->findForWorkspaces($workspaceIds);
                break;
            case \App\ApiResource\Group::class:
                $results = $this->entityManager->getRepository(Group::class)->findForWorkspaces($workspaceIds);
                break;
            case \App\ApiResource\Label::class:
                $results = $this->entityManager->getRepository(Label::class)->findForWorkspaces($workspaceIds);
                break;
            case \App\ApiResource\Milestone::class:
                $results = $this->entityManager->getRepository(Milestone::class)->findForWorkspaces($workspaceIds);
                break;
            case \App\ApiResource\Project::class:
                $results = $this->entityManager->getRepository(Project::class)->findForWorkspaces($workspaceIds);
                break;
            case \App\ApiResource\Team::class:
                $results = $this->entityManager->getRepository(Team::class)->findForWorkspaces($workspaceIds);
                break;
            default:
                throw new LogicException(sprintf('Unknown operation class "%s"', $operation->getClass()));
        }

        return $this->transformer->transform($results);
    }
}
