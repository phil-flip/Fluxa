<?php

namespace App\Repository;

use App\Entity\Label;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

class LabelRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Label::class);
    }

    /**
     * @param string[] $workspaceIds
     * @param array $criteria
     * @return Label[]
     */
    public function findForWorkspaces(array $workspaceIds, array $criteria = []): array
    {
        $queryBuilder = $this->createQueryBuilder('label')
            ->leftJoin('label.team', 'team')
            ->leftJoin('label.project', 'project');

        QueryBuilderHelper::addWorkspaceFilter(['team', 'project'], $workspaceIds, $queryBuilder);
        QueryBuilderHelper::processCriteria('label', $criteria, $queryBuilder);

        return $queryBuilder->getQuery()->execute();
    }
}
