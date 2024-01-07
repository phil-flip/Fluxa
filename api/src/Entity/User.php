<?php

namespace App\Entity;

use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\Entity;
use Doctrine\ORM\Mapping\Table;

#[Entity]
#[Table(name: '"user"')]
class User
{
    use UuidTrait;

    #[Column(type: 'string')]
    public string $username;

    #[Column(type: 'string')]
    public string $name;

    #[Column(type: 'string', nullable: true)]
    public ?string $profilePhotoUrl;
}