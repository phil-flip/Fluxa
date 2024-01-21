<?php

namespace App\Transformer;

use App\ApiResource\User as Input;
use App\Entity\User as Output;
use App\Entity\Workspace;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Exception\ORMException;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

/**
 * @implements Transformer<Input, Output>
 */
readonly class ResourceUserTransformer extends AbstractTransformer implements Transformer
{
    public function __construct(
        private UserPasswordHasherInterface $passwordHasher,
        private EntityManagerInterface $entityManager,
    ) {
    }

    public function transform($data, array $context = []): Output
    {
        $resource = new Output();
        $resource->name = $data->name;
        $resource->photoUrl = $data->photoUrl;
        $resource->emailAddress = $data->emailAddress;

        if ($data->password) {
            $resource->password = $this->passwordHasher->hashPassword($resource, $data->password);
        }

        $resource->workspaces->add($this->entityManager->getReference(Workspace::class, $data->workspaceId));

        return $resource;
    }

    public function supports($data): bool
    {
        return $data instanceof Input;
    }
}
