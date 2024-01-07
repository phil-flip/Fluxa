<?php

namespace App\State;

use ApiPlatform\Metadata\Get;
use ApiPlatform\Metadata\Operation;
use ApiPlatform\Metadata\Patch;
use ApiPlatform\State\ProviderInterface;
use App\Entity\Task;
use App\Transformer\TransformerChain;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

readonly class TaskProvider implements ProviderInterface
{
    public function __construct(private EntityManagerInterface $entityManager, private TransformerChain $transformer)
    {
    }

    public function provide(Operation $operation, array $uriVariables = [], array $context = []): object|array|null
    {
        if ($operation instanceof Patch || $operation instanceof Get) {
            $data = $this->entityManager->getRepository(Task::class)->find($uriVariables['id']);
            if (!$data) {
                throw new NotFoundHttpException("No task found with ID '{$uriVariables['id']}'.");
            }
        } else {
            $findByCriteria = [];
            $filters = $context['filters'] ?? [];
            if (isset($filters['team_id'])) {
                $findByCriteria['team'] = $filters['team_id'];
            }

            $data = $this->entityManager->getRepository(Task::class)->findBy($findByCriteria);
        }

        return $this->transformer->transform($data);
    }
}