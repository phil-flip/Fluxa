<?php

namespace App\ApiResource;

use ApiPlatform\Metadata\ApiResource;
use App\State\GenericProcessor;
use App\State\GenericProvider;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints\NotBlank;

#[ApiResource(
    normalizationContext: ['groups' => ['read']],
    denormalizationContext: ['groups' => ['write']],
    provider: GenericProvider::class,
    processor: GenericProcessor::class,
)]
class Milestone
{
    #[Groups(['read'])]
    public string $id;

    #[Groups(['read', 'write'])]
    public ?int $sortOrder;

    #[Groups(['read', 'write'])]
    public string $name;

    #[NotBlank()]
    #[Groups(['read', 'write'])]
    public string $projectId;
}
