<?php

namespace App\Entity;

use App\Repository\TeamRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\Entity;
use Doctrine\ORM\Mapping\ManyToMany;
use Doctrine\ORM\Mapping\ManyToOne;
use Doctrine\ORM\Mapping\OneToMany;
use Doctrine\ORM\Mapping\OneToOne;

#[Entity(repositoryClass: TeamRepository::class)]
class Team
{
    use UuidTrait;

    #[Column(type: 'string')]
    public string $name;

    #[Column(type: 'string')]
    public string $slug;

    #[Column(type: 'json', options: ['default' => '{}'])]
    public array $properties = [];

    #[OneToOne(targetEntity: Workflow::class, cascade: ['all'])]
    public Workflow $workflow;

    #[ManyToOne(targetEntity: Workspace::class)]
    public Workspace $workspace;

    /** @var ArrayCollection<Cycle>|Cycle[] */
    #[OneToMany(mappedBy: 'team', targetEntity: Cycle::class)]
    public iterable $cycles;

    /** @var ArrayCollection<Project>|Project[] */
    #[ManyToMany(targetEntity: Project::class)]
    public iterable $projects;

    /** @var ArrayCollection<Group>|Group[] */
    #[OneToMany(mappedBy: 'team', targetEntity: Group::class)]
    public iterable $groups;

    /** @var ArrayCollection<Label>|Label[] */
    #[OneToMany(mappedBy: 'team', targetEntity: Label::class)]
    public iterable $labels;

    public function __construct()
    {
        $this->cycles = new ArrayCollection();
        $this->projects = new ArrayCollection();
        $this->groups = new ArrayCollection();
        $this->labels = new ArrayCollection();
    }
}
