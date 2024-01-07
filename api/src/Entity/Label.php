<?php

namespace App\Entity;

use App\Repository\LabelRepository;
use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\Entity;
use Doctrine\ORM\Mapping\JoinColumn;
use Doctrine\ORM\Mapping\ManyToOne;

#[Entity(repositoryClass: LabelRepository::class)]
class Label
{
    use UuidTrait;

    #[Column(type: 'string')]
    public string $name;

    #[Column(type: 'json', options: ['default' => '{}'])]
    public array $properties = [];

    #[JoinColumn(nullable: true)]
    #[ManyToOne(targetEntity: Project::class, inversedBy: 'labels')]
    public ?Project $project = null;

    #[JoinColumn(nullable: true)]
    #[ManyToOne(targetEntity: Team::class, inversedBy: 'labels')]
    public ?Team $team = null;
}
