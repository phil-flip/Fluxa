<?php

namespace App\State;

use ApiPlatform\Metadata\Operation;
use ApiPlatform\State\ProcessorInterface;
use App\Transformer\TransformerChain;
use Doctrine\ORM\EntityManagerInterface;

readonly class GenericProcessor implements ProcessorInterface
{
    public function __construct(
        private EntityManagerInterface $entityManager,
        private TransformerChain $transformer
    ) {
    }

    public function process(
        mixed $data,
        Operation $operation,
        array $uriVariables = [],
        array $context = []
    ): mixed {
        $entity = $this->transformer->transform($data);

        $this->entityManager->persist($entity);
        $this->entityManager->flush();

        return $this->transformer->transform($entity);
    }
}