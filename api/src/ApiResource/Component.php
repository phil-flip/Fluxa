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
class Component
{
    #[Groups(['read'])]
    public string $id;

    #[NotBlank()]
    #[Groups(['read', 'write'])]
    public string $name;

    #[NotBlank()]
    #[Groups(['read', 'write'])]
    public string $projectId;
}
