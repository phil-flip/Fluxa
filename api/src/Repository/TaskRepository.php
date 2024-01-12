<?php

namespace App\Repository;

use App\Entity\Task;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\DBAL\Exception;
use Doctrine\Persistence\ManagerRegistry;

class TaskRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Task::class);
    }

    /**
     * @param string[] $workspaceIds
     * @param array $criteria
     * @return Task[]
     */
    public function findForWorkspaces(array $workspaceIds, array $criteria = []): array
    {
        $queryBuilder = $this->createQueryBuilder('task')
            ->innerJoin('task.team', 'team');

        QueryBuilderHelper::addWorkspaceFilter('team', $workspaceIds, $queryBuilder);
        QueryBuilderHelper::processCriteria('task', $criteria, $queryBuilder);

        return $queryBuilder->getQuery()->execute();
    }

    /**
     * @throws Exception
     */
    public function insertTask(Task $task): void
    {
        $entityManager = $this->getEntityManager();
        $entityManager->beginTransaction();

        try {
            $task->number = $this->getNextTaskNumber($task->project->getId());
            $entityManager->persist($task);

            $entityManager->flush();
            $entityManager->commit();
        } catch (\Exception $e) {
            $entityManager->rollback();

            throw $e;
        }
    }

    /**
     * @throws Exception
     */
    public function getNextTaskNumber(string $projectId): int
    {
        $entityManager = $this->getEntityManager();

        $query = $entityManager->getConnection()->prepare(
            "SELECT COALESCE(MAX(t.number), 0) + 1
            FROM task t
            WHERE t.project_id = :project_id"
        );
        $query->bindValue('project_id', $projectId);

        return (int)$query->executeQuery()->fetchOne();
    }
}
