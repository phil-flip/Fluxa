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

    /**
     * @param string[] $workspaceIds
     * @param array $criteria
     * @return Group[]
     */
    public function findForWorkspaces(array $workspaceIds, array $criteria = []): array
    {
        $queryBuilder = $this->createQueryBuilder('g')
            ->leftJoin('g.team', 'team')
            ->leftJoin('g.project', 'project');

        QueryBuilderHelper::addWorkspaceFilter(['team', 'project'], $workspaceIds, $queryBuilder);
        QueryBuilderHelper::processCriteria('g', $criteria, $queryBuilder);

        return $queryBuilder->getQuery()->execute();
    }
}
