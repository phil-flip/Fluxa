<?php

namespace App\Repository;

use App\Entity\Component;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

class ComponentRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Component::class);
    }

    /**
     * @param string[] $workspaceIds
     * @param array $criteria
     * @return Component[]
     */
    public function findForWorkspaces(array $workspaceIds, array $criteria = []): array
    {
        $queryBuilder = $this->createQueryBuilder('component')
            ->innerJoin('component.project', 'project');

        QueryBuilderHelper::addWorkspaceFilter('project', $workspaceIds, $queryBuilder);
        QueryBuilderHelper::processCriteria('component', $criteria, $queryBuilder);

        return $queryBuilder->getQuery()->execute();
    }
}
