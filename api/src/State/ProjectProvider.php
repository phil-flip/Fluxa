<?php

namespace App\State;

use ApiPlatform\Metadata\Get;
use ApiPlatform\Metadata\Operation;
use ApiPlatform\State\ProviderInterface;
use App\Repository\ProjectRepository;
use App\Transformer\TransformerChain;

readonly class ProjectProvider implements ProviderInterface
{
    public function __construct(private ProjectRepository $projectRepository, private TransformerChain $transformer)
    {
    }

    public function provide(Operation $operation, array $uriVariables = [], array $context = []): object|array|null
    {
        if ($operation instanceof Get) {
            $project = $this->projectRepository->findOneBy([
                'code' => $uriVariables['code'],
            ]);

            return $this->transformer->transform($project);
        } else {
            $projects = $this->projectRepository->findAll();

            return $this->transformer->transform($projects);
        }
    }
}