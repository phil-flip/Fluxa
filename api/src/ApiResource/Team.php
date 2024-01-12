<?php

namespace App\ApiResource;

use ApiPlatform\Metadata\ApiResource;
use App\State\GenericProvider;
use App\State\TeamProcessor;
use Symfony\Component\Serializer\Annotation\Context;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Serializer\Normalizer\AbstractObjectNormalizer;
use Symfony\Component\Serializer\Serializer;
use Symfony\Component\Validator\Constraints\NotBlank;

#[ApiResource(
    normalizationContext: ['groups' => ['read']],
    denormalizationContext: ['groups' => ['write']],
    provider: GenericProvider::class,
    processor: TeamProcessor::class,
)]
class Team
{
    #[Groups(['read'])]
    public string $id;

    #[NotBlank()]
    #[Groups(['read', 'write'])]
    public string $name;

    #[Groups(['read'])]
    public string $slug;

    #[Groups(['read'])]
    #[Context([
        Serializer::EMPTY_ARRAY_AS_OBJECT => true,
        AbstractObjectNormalizer::PRESERVE_EMPTY_OBJECTS => true,
    ])]
    public array $properties;

    /** @var string[] */
    #[Groups(['read'])]
    public iterable $projectIds;

    /** @var string[] */
    #[Groups(['read'])]
    public iterable $cycleIds;

    #[Groups(['read'])]
    public Workflow $workflow;

    #[Groups(['write'])]
    public string $workspaceId;

    /** @var string[] */
    #[Groups(['read'])]
    public iterable $labelIds = [];

    /** @var string[] */
    #[Groups(['read'])]
    public iterable $groupIds = [];
}
