<?php

namespace App\ApiResource;

use ApiPlatform\Metadata\ApiFilter;
use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\Get;
use ApiPlatform\Metadata\GetCollection;
use ApiPlatform\Metadata\Post;
use App\Filter\QueryFilter;
use App\State\TaskProcessor;
use App\State\TaskProvider;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints\NotBlank;

#[ApiResource(
    normalizationContext: ['groups' => ['read']],
    denormalizationContext: ['groups' => ['write']],
    provider: TaskProvider::class,
    processor: TaskProcessor::class,
)]
#[Post(validationContext: ['groups' => ['Default', 'post']])]
#[Get]
#[GetCollection]
#[ApiFilter(QueryFilter::class, arguments: [
    'description' => [
        'team_id' => [
            'property' => 'name',
            'type' => 'string',
            'required' => false,
            'strategy' => 'exact',
        ],
    ],
])]
class Task
{
    #[Groups(['read'])]
    public string $id;

    #[Groups(['read'])]
    public int $number;

    #[NotBlank(groups: ['post'])]
    #[Groups(['read', 'write'])]
    public string $title;

    #[Groups(['read', 'write'])]
    public ?string $description = null;

    #[Groups(['read', 'write'])]
    public ?int $urgency;

    #[NotBlank(groups: ['post'])]
    #[Groups(['read', 'write'])]
    public string $statusId;

    #[NotBlank(groups: ['post'])]
    #[Groups(['read', 'write'])]
    public string $projectId;

    #[NotBlank(groups: ['post'])]
    #[Groups(['read', 'write'])]
    public ?string $teamId = null;

    #[Groups(['read', 'write'])]
    public ?string $milestoneId = null;

    #[Groups(['read', 'write'])]
    public ?string $cycleId = null;

    #[Groups(['read', 'write'])]
    public ?string $assigneeId = null;

    /** @var string[] */
    #[Groups(['read', 'write'])]
    public iterable $labelIds = [];

    /** @var string[] */
    #[Groups(['read', 'write'])]
    public iterable $componentIds = [];

    /** @var string[] */
    #[Groups(['write', 'read'])]
    public iterable $projectGroupIds = [];

    /** @var string[] */
    #[Groups(['write', 'read'])]
    public iterable $teamGroupIds = [];

    #[Groups(['read'])]
    public string $createdAt;

    #[Groups(['read'])]
    public ?string $updatedAt = null;
}
