<?php

namespace App\Entity;

use App\Repository\MilestoneRepository;
use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\Entity;
use Doctrine\ORM\Mapping\JoinColumn;
use Doctrine\ORM\Mapping\ManyToOne;

#[Entity(repositoryClass: MilestoneRepository::class)]
class Milestone
{
    use UuidTrait;

    #[Column(type: 'string')]
    public string $name;

    #[Column(type: 'integer', nullable: true)]
    public ?int $sortOrder = null;

    #[JoinColumn(nullable: false)]
    #[ManyToOne(targetEntity: Project::class, inversedBy: 'milestones')]
    public Project $project;
}