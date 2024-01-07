<?php

namespace App\ApiResource;

use ApiPlatform\Metadata\ApiResource;
use App\State\GenericProcessor;
use App\State\GenericProvider;
use Symfony\Component\Serializer\Annotation\Groups;

#[ApiResource(
    normalizationContext: ['groups' => ['read']],
    denormalizationContext: ['groups' => ['write']],
    provider: GenericProvider::class,
    processor: GenericProcessor::class,
)]
class Group
{
    #[Groups(['read'])]
    public string $id;

    #[Groups(['read', 'write'])]
    public string $name;

//    #[NotBlank()] todo: check based on group
    #[Groups(['read', 'write'])]
    public ?string $projectId = null;

//    #[NotBlank()] todo: check based on group
    #[Groups(['read', 'write'])]
    public ?string $teamId = null;
}
