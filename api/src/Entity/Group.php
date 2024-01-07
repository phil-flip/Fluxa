<?php

namespace App\Entity;

use App\Repository\GroupRepository;
use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\Entity;
use Doctrine\ORM\Mapping\JoinColumn;
use Doctrine\ORM\Mapping\ManyToOne;
use Doctrine\ORM\Mapping\Table;

#[Entity(repositoryClass: GroupRepository::class)]
#[Table(name: '"group"')]
class Group
{
    use UuidTrait;

    #[Column(type: 'text', nullable: false)]
    public string $name;

    #[JoinColumn(nullable: true)]
    #[ManyToOne(targetEntity: Project::class, inversedBy: 'groups')]
    public ?Project $project = null;

    #[JoinColumn(nullable: true)]
    #[ManyToOne(targetEntity: Team::class, inversedBy: 'groups')]
    public ?Team $team = null;
}