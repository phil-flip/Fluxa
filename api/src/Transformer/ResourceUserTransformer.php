<?php

namespace App\Transformer;

use App\ApiResource\User as Input;
use App\Entity\User as Output;
use App\Entity\Workspace;
use Doctrine\ORM\EntityManagerInterface;
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
        $resource->emailAddress = $data->emailAddress;
        $resource->name = $data->name;
        $resource->photoUrl = $data->photoUrl;

        if ($data->password) {
            $resource->password = $this->passwordHasher->hashPassword($resource, $data->password);
        }

        if ($data->workspaceId) {
            $resource->workspaces->add($this->entityManager->getReference(Workspace::class, $data->workspaceId));
        }

        return $resource;
    }

    public function supports($data): bool
    {
        return $data instanceof Input;
    }
}
