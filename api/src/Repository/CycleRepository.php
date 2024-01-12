<?php

namespace App\Repository;

use App\Entity\Cycle;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

class CycleRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Cycle::class);
    }

    /**
     * @param string[] $workspaceIds
     * @param array $criteria
     * @return Cycle[]
     */
    public function findForWorkspaces(array $workspaceIds, array $criteria = []): array
    {
        $queryBuilder = $this->createQueryBuilder('cycle')
            ->innerJoin('cycle.team', 'team');

        QueryBuilderHelper::addWorkspaceFilter('team', $workspaceIds, $queryBuilder);
        QueryBuilderHelper::processCriteria('cycle', $criteria, $queryBuilder);

        return $queryBuilder->getQuery()->execute();
    }
}
