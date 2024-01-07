<?php

namespace App\Entity;

use App\Repository\CycleRepository;
use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\Entity;
use Doctrine\ORM\Mapping\JoinColumn;
use Doctrine\ORM\Mapping\ManyToOne;

#[Entity(repositoryClass: CycleRepository::class)]
class Cycle
{
    use UuidTrait;

    #[Column(type: 'text', nullable: false)]
    public string $name;

    #[JoinColumn(nullable: false)]
    #[ManyToOne(targetEntity: Team::class, inversedBy: 'cycles')]
    public Team $team;
}