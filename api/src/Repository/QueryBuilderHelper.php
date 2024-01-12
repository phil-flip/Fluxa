<?php

namespace App\Repository;

use Doctrine\ORM\QueryBuilder;

class QueryBuilderHelper
{
    public static function addWorkspaceFilter(
        string|array $alias,
        array $workspaceIds,
        QueryBuilder $queryBuilder
    ): void {
        $whereStatements = array_map(function (string $alias) {
            return sprintf('%s.workspace IN(:workspace)', $alias);
        }, (array)$alias);

        $queryBuilder->andWhere($queryBuilder->expr()->orX(...$whereStatements));
        $queryBuilder->setParameter('workspace', $workspaceIds);
    }

    public static function processCriteria(string $alias, array $criteria, QueryBuilder $queryBuilder): void
    {
        foreach ($criteria as $criterionKey => $criterionValue) {
            $queryBuilder->andWhere(sprintf('%1$s.%2$s = :%2$s', $alias, $criterionKey));
            $queryBuilder->setParameter($criterionKey, $criterionValue);
        }
    }
}
