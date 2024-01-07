<?php

namespace App\ApiResource;

use ApiPlatform\Metadata\ApiResource;
use App\State\GenericProcessor;
use App\State\GenericProvider;
use Symfony\Component\Serializer\Annotation\Context;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Serializer\Normalizer\AbstractObjectNormalizer;
use Symfony\Component\Serializer\Serializer;
use Symfony\Component\Validator\Constraints\NotBlank;

#[ApiResource(
    normalizationContext: ['groups' => ['read']],
    denormalizationContext: ['groups' => ['write']],
    provider: GenericProvider::class,
    processor: GenericProcessor::class,
)]
class Label
{
    #[Groups(['read'])]
    public string $id;

    #[Groups(['read', 'write'])]
    public string $name;

    #[Groups(['read'])]
    #[Context([
        Serializer::EMPTY_ARRAY_AS_OBJECT => true,
        AbstractObjectNormalizer::PRESERVE_EMPTY_OBJECTS => true,
    ])]
    public array $properties;

//    #[NotBlank()] todo: check based on group
    #[Groups(['read', 'write'])]
    public ?string $projectId = null;

//    #[NotBlank()] todo: check based on group
    #[Groups(['read', 'write'])]
    public ?string $teamId = null;
}
