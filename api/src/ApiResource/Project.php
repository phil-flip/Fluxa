<?php

namespace App\ApiResource;

use ApiPlatform\Metadata\ApiProperty;
use ApiPlatform\Metadata\ApiResource;
use App\State\ProjectProcessor;
use App\State\ProjectProvider;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints\NotBlank;

#[ApiResource(
    normalizationContext: ['groups' => ['read']],
    denormalizationContext: ['groups' => ['write']],
    provider: ProjectProvider::class,
    processor: ProjectProcessor::class,
)]
class Project
{
    #[ApiProperty(identifier: false)]
    #[Groups(['read'])]
    public string $id;

    #[NotBlank()]
    #[Groups(['write'])]
    public string $teamId;

    #[NotBlank()]
    #[Groups(['read', 'write'])]
    public string $name;

    #[ApiProperty(identifier: true)]
    #[Groups(['read'])]
    public string $code;

    /** @var string[] */
    #[Groups(['read'])]
    public iterable $labelIds = [];

    /** @var string[] */
    #[Groups(['read'])]
    public iterable $milestoneIds = [];

    /** @var string[] */
    #[Groups(['read'])]
    public iterable $componentIds = [];

    /** @var string[] */
    #[Groups(['read'])]
    public iterable $groupIds = [];
}
