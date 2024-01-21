<?php

namespace App\ApiResource;

use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\Post;
use App\State\GenericProcessor;
use Symfony\Component\Serializer\Annotation\Groups;

#[ApiResource(
    normalizationContext: ['groups' => ['read']],
    denormalizationContext: ['groups' => ['write']],
    processor: GenericProcessor::class,
)]
#[Post(validationContext: ['groups' => ['Default', 'post']])]
class Workspace
{
    #[Groups(['read'])]
    public string $id;

    #[Groups(['read', 'write'])]
    public string $slug;
}
