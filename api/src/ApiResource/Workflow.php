<?php

namespace App\ApiResource;

use ApiPlatform\Metadata\ApiResource;
use App\State\GenericProvider;
use Symfony\Component\Serializer\Annotation\Groups;

#[ApiResource(
    normalizationContext: ['groups' => ['read']],
    denormalizationContext: ['groups' => ['write']],
    provider: GenericProvider::class,
)]
class Workflow
{
    #[Groups(['read'])]
    public string $id;

    /** @var Status[] */
    #[Groups(['read'])]
    public iterable $statuses;
}