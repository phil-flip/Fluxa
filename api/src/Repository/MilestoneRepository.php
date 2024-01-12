<?php

namespace App\Repository;

use App\Entity\Milestone;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

class MilestoneRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Milestone::class);
    }

    /**
     * @param string[] $workspaceIds
     * @param array $criteria
     * @return Milestone[]
     */
    public function findForWorkspaces(array $workspaceIds, array $criteria = []): array
    {
        $queryBuilder = $this->createQueryBuilder('milestone')
            ->innerJoin('milestone.project', 'project');

        QueryBuilderHelper::addWorkspaceFilter('project', $workspaceIds, $queryBuilder);
        QueryBuilderHelper::processCriteria('milestone', $criteria, $queryBuilder);

        return $queryBuilder->getQuery()->execute();
    }
}
