<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\Entity;
use Doctrine\ORM\Mapping\ManyToMany;
use Doctrine\ORM\Mapping\Table;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;

#[Entity]
#[Table(name: '"user"')]
class User implements UserInterface, PasswordAuthenticatedUserInterface
{
    use UuidTrait;

    #[Column(type: 'string', unique: true)]
    public string $emailAddress;

    #[Column(type: 'string', nullable: true)]
    public ?string $password;

    #[Column(type: 'string')]
    public string $name;

    #[Column(type: 'string', nullable: true)]
    public ?string $photoUrl;

    /** @var ArrayCollection<Workspace>|Workspace[] */
    #[ManyToMany(targetEntity: Workspace::class)]
    public iterable $workspaces;

    public function __construct()
    {
        $this->workspaces = new ArrayCollection();
    }

    public function getPassword(): ?string
    {
        return $this->password;
    }

    public function getRoles(): array
    {
        return ['ROLE_USER'];
    }

    public function eraseCredentials()
    {
    }

    public function getUserIdentifier(): string
    {
        return $this->emailAddress;
    }
}
