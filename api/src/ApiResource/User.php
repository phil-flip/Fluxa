<?php

namespace App\ApiResource;

use ApiPlatform\Metadata\ApiResource;
use Symfony\Component\Serializer\Annotation\Groups;

#[ApiResource]
class User
{
    #[Groups(['read'])]
    public string $id;

    #[Groups(['read'])]
    public string $name;

    #[Groups(['read'])]
    public string $photoUrl;
}