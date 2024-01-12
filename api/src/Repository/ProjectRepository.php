<?php

namespace App\Repository;

use App\Entity\Project;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

class ProjectRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Project::class);
    }

    /**
     * @param string[] $workspaceIds
     * @param array $criteria
     * @return Project[]
     */
    public function findForWorkspaces(array $workspaceIds, array $criteria = []): array
    {
        $queryBuilder = $this->createQueryBuilder('project');

        QueryBuilderHelper::addWorkspaceFilter('project', $workspaceIds, $queryBuilder);
        QueryBuilderHelper::processCriteria('project', $criteria, $queryBuilder);

        return $queryBuilder->getQuery()->execute();
    }
}
