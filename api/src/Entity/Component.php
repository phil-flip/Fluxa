<?php

namespace App\Entity;

use App\Repository\ComponentRepository;
use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\Entity;
use Doctrine\ORM\Mapping\JoinColumn;
use Doctrine\ORM\Mapping\ManyToOne;

#[Entity(repositoryClass: ComponentRepository::class)]
class Component
{
    use UuidTrait;

    #[Column(type: 'string')]
    public string $name;

    #[JoinColumn(nullable: false)]
    #[ManyToOne(targetEntity: Project::class, inversedBy: 'components')]
    public Project $project;
}