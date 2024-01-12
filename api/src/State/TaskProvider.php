<?php

namespace App\State;

use ApiPlatform\Metadata\Get;
use ApiPlatform\Metadata\Operation;
use ApiPlatform\Metadata\Patch;
use ApiPlatform\State\ProviderInterface;
use App\Entity\User;
use App\Entity\Workspace;
use App\Repository\TaskRepository;
use App\Transformer\TransformerChain;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

readonly class TaskProvider implements ProviderInterface
{
    public function __construct(
        private TaskRepository $taskRepository,
        private TransformerChain $transformer,
        private TokenStorageInterface $tokenStorage,
    ) {
    }

    public function provide(Operation $operation, array $uriVariables = [], array $context = []): object|array|null
    {
        if ($operation instanceof Patch || $operation instanceof Get) {
            $data = $this->taskRepository->find($uriVariables['id']);
            if (!$data) {
                throw new NotFoundHttpException("No task found with ID '{$uriVariables['id']}'.");
            }
        } else {
            $findByCriteria = [];
            $filters = $context['filters'] ?? [];
            if (isset($filters['team_id'])) {
                $findByCriteria['team'] = $filters['team_id'];
            }

            /** @var User $user */
            $user = $this->tokenStorage->getToken()?->getUser();
            $workspaceIds = $user->workspaces->map(fn(Workspace $workspace) => $workspace->getId())->toArray();

            $data = $this->taskRepository->findForWorkspaces($workspaceIds, $findByCriteria);
        }

        return $this->transformer->transform($data);
    }
}
