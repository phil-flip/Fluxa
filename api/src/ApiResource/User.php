<?php

namespace App\ApiResource;

use ApiPlatform\Metadata\ApiProperty;
use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\Post;
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
#[Post(validationContext: ['groups' => ['Default', 'post']])]
class User
{
    #[Groups(['read'])]
    public string $id;

    #[NotBlank(groups: ['post'])]
    #[Groups(['read', 'write'])]
    public string $name;

    #[NotBlank(groups: ['post'])]
    #[Groups(['read', 'write'])]
    public string $emailAddress;

    #[Groups(['read', 'write'])]
    public ?string $photoUrl = null;

    #[NotBlank(groups: ['post'])]
    #[Groups(['write'])]
    #[ApiProperty(description: 'A plain text password')]
    public string $password;

    #[NotBlank(groups: ['post'])]
    #[Groups(['write'])]
    public string $workspaceId;
}
