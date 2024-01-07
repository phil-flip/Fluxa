<?php

namespace App\ApiResource;

use ApiPlatform\Metadata\ApiResource;
use Symfony\Component\Serializer\Annotation\Groups;

#[ApiResource]
class Status
{
    #[Groups(['read'])]
    public string $id;

    #[Groups(['read'])]
    public string $name;

    #[Groups(['read'])]
    public int $sortOrder;
}