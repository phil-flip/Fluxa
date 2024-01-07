<?php

namespace App\State;

use ApiPlatform\Metadata\Operation;
use ApiPlatform\State\ProcessorInterface;
use App\ApiResource\Task as TaskResource;
use App\Repository\TaskRepository;
use App\Transformer\TransformerChain;
use Doctrine\DBAL\Exception;

readonly class TaskProcessor implements ProcessorInterface
{
    public function __construct(
        private TaskRepository $taskRepository,
        private TransformerChain $transformer
    ) {
    }

    /**
     * @throws Exception
     */
    public function process(
        mixed $data,
        Operation $operation,
        array $uriVariables = [],
        array $context = []
    ): TaskResource {
        $taskEntity = $this->transformer->transform($data);
        $this->taskRepository->insertTask($taskEntity);

        return $this->transformer->transform($taskEntity);
    }
}