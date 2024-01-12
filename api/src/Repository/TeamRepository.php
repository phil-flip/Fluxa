<?php

namespace App\Repository;

use App\Entity\Team;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Team|null find($id, $lockMode = null, $lockVersion = null)
 */
class TeamRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Team::class);
    }

    /**
     * @param string[] $workspaceIds
     * @param array $criteria
     * @return Team[]
     */
    public function findForWorkspaces(array $workspaceIds, array $criteria = []): array
    {
        $queryBuilder = $this->createQueryBuilder('team');

        QueryBuilderHelper::addWorkspaceFilter('team', $workspaceIds, $queryBuilder);
        QueryBuilderHelper::processCriteria('team', $criteria, $queryBuilder);

        return $queryBuilder->getQuery()->execute();
    }
}
