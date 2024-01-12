<?php

namespace App\Entity;

use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\Entity;

#[Entity]
class Workspace
{
    use UuidTrait;

    #[Column(type: 'string')]
    public string $slug;
}
