<?php

namespace App\Controller;

use App\Entity\Task;
use App\Transformer\TransformerChain;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\PropertyAccess\PropertyAccess;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/tasks/{id}', methods: [Request::METHOD_PATCH])]
class PatchTaskAction extends AbstractController
{
    public function __construct(
        private readonly EntityManagerInterface $entityManager,
        private readonly TransformerChain $transformer,
    ) {
    }

    public function __invoke(Request $request): Response
    {
        $id = (string)$request->attributes->get('id');

        $taskEntity = $this->entityManager->getRepository(Task::class)->find($id);
        if (!$taskEntity) {
            throw new NotFoundHttpException("No task found with ID '{$id}'.");
        }

        $taskResource = $this->transformer->transform($taskEntity);
        $propertyAccessor = PropertyAccess::createPropertyAccessor();
        foreach ($request->toArray() as $propertyName => $value) {
            if ($propertyAccessor->isWritable($taskResource, $propertyName)) {
                $propertyAccessor->setValue($taskResource, $propertyName, $value);
            }
        }

        $this->transformer->transform($taskResource, [
            'object_to_populate' => $taskEntity,
        ]);

        $this->entityManager->flush();

        return new Response(null, Response::HTTP_NO_CONTENT);
    }
}
