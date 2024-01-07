<?php

namespace App\Entity;

use App\Repository\WorkflowRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping\Entity;
use Doctrine\ORM\Mapping\OneToMany;

#[Entity(repositoryClass: WorkflowRepository::class)]
class Workflow
{
    use UuidTrait;

    #[OneToMany(mappedBy: 'workflow', targetEntity: Status::class)]
    public Collection $statuses;

    public function __construct()
    {
        $this->statuses = new ArrayCollection();
    }
}