<?php

namespace App\Entity;

use App\Repository\ProjectRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\Entity;
use Doctrine\ORM\Mapping\HasLifecycleCallbacks;
use Doctrine\ORM\Mapping\ManyToOne;
use Doctrine\ORM\Mapping\OneToMany;

#[Entity(repositoryClass: ProjectRepository::class)]
#[HasLifecycleCallbacks]
class Project
{
    use UuidTrait;
    use CreatedUpdatedTrait;

    #[Column(type: 'string')]
    public string $name;

    #[Column(type: 'string')]
    public string $code;

    #[ManyToOne(targetEntity: Workspace::class)]
    public Workspace $workspace;

    /** @var ArrayCollection<Label>|Label[] */
    #[OneToMany(mappedBy: 'project', targetEntity: Label::class)]
    public iterable $labels;

    /** @var ArrayCollection<Milestone>|Milestone[] */
    #[OneToMany(mappedBy: 'project', targetEntity: Milestone::class)]
    public iterable $milestones;

    /** @var ArrayCollection<Component>|Component[] */
    #[OneToMany(mappedBy: 'project', targetEntity: Component::class)]
    public iterable $components;

    /** @var ArrayCollection<Group>|Group[] */
    #[OneToMany(mappedBy: 'project', targetEntity: Group::class)]
    public iterable $groups;

    public function __construct()
    {
        $this->labels = new ArrayCollection();
        $this->milestones = new ArrayCollection();
        $this->components = new ArrayCollection();
        $this->groups = new ArrayCollection();
    }
}
