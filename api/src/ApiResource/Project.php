<?php

namespace App\ApiResource;

use ApiPlatform\Metadata\ApiProperty;
use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\Get;
use ApiPlatform\Metadata\GetCollection;
use ApiPlatform\Metadata\Post;
use App\State\GenericProvider;
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
#[Get()]
#[GetCollection(provider: GenericProvider::class)]
#[Post()]
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

    #[Groups(['write'])]
    public string $workspaceId;

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
