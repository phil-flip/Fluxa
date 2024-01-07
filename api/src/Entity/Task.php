<?php

namespace App\Entity;

use App\Repository\TaskRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\Entity;
use Doctrine\ORM\Mapping\ManyToOne;

#[Entity(repositoryClass: TaskRepository::class)]
#[ORM\UniqueConstraint(columns: ['number', 'project_id'])]
#[ORM\HasLifecycleCallbacks]
class Task
{
    use UuidTrait;
    use CreatedUpdatedTrait;

    #[ORM\Column(type: 'integer')]
    public int $number;

    #[ORM\Column(type: 'integer', nullable: true)]
    public ?int $urgency = null;

    #[ORM\Column(type: 'string')]
    public string $title;

    #[ORM\Column(type: 'text', nullable: true)]
    public ?string $description = null;

    #[ORM\JoinColumn(nullable: false)]
    #[ManyToOne(targetEntity: Status::class)]
    public Status $status;

    #[ORM\JoinColumn(nullable: false)]
    #[ManyToOne(targetEntity: Project::class)]
    public Project $project;

    #[ORM\JoinColumn(nullable: false)]
    #[ManyToOne(targetEntity: Team::class)]
    public Team $team;

    #[ORM\JoinColumn(nullable: true)]
    #[ManyToOne(targetEntity: Milestone::class)]
    public ?Milestone $milestone = null;

    #[ORM\JoinColumn(nullable: true)]
    #[ManyToOne(targetEntity: Cycle::class)]
    public ?Cycle $cycle = null;

    #[ORM\JoinColumn(nullable: true)]
    #[ManyToOne(targetEntity: User::class)]
    public ?User $assignee = null;

    /** @var ArrayCollection<Component>|Component[] */
    #[ORM\ManyToMany(targetEntity: Component::class, fetch: 'EAGER')]
    public iterable $components;

    /** @var ArrayCollection<Group>|Group[] */
    #[ORM\ManyToMany(targetEntity: Group::class, fetch: 'EAGER')]
    public iterable $groups;

    /** @var ArrayCollection<Label>|Label[] */
    #[ORM\ManyToMany(targetEntity: Label::class, fetch: 'EAGER')]
    public iterable $labels;

    public function __construct()
    {
        $this->components = new ArrayCollection();
        $this->groups = new ArrayCollection();
        $this->labels = new ArrayCollection();
    }
}
