<?php

namespace App\Repository;

use App\Entity\Group;
use App\Enum\GroupType;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

class GroupRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Group::class);
    }

    public function findByGroupType(GroupType $type): array
    {
        $queryBuilder = $this->createQueryBuilder('g');

        if ($type === GroupType::TEAM) {
            $queryBuilder->where('g.team IS NOT NULL');
        } else {
            $queryBuilder->where('g.project IS NOT NULL');
        }

        return $queryBuilder->getQuery()->execute();
    }
}