<?php

namespace App\Entity;

use App\Repository\StatusRepository;
use App\System\SystemState;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\Entity;
use Doctrine\ORM\Mapping\ManyToOne;

#[Entity(repositoryClass: StatusRepository::class)]
class Status
{
    use UuidTrait;

    #[ORM\Column(type: 'text', nullable: false)]
    public string $name;

    #[ORM\Column(type: 'integer', nullable: false, enumType: SystemState::class)]
    public SystemState $state;

    #[ManyToOne(targetEntity: Workflow::class, inversedBy: 'statuses')]
    #[ORM\JoinColumn(nullable: false)]
    public Workflow $workflow;
}
