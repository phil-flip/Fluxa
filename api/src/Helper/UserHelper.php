<?php

namespace App\Helper;

use App\Entity\User;
use App\Entity\Workspace;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

class UserHelper
{
    public static function getWorkspaceIds(TokenStorageInterface $tokenStorage): array
    {
        /** @var User $user */
        $user = $tokenStorage->getToken()?->getUser();
        return $user->workspaces->map(fn(Workspace $workspace) => $workspace->getId())->toArray();
    }
}
