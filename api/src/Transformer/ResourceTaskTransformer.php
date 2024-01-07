<?php

namespace App\Transformer;

use App\ApiResource\Task as TaskResource;
use App\Entity\Component as ComponentEntity;
use App\Entity\Cycle as CycleEntity;
use App\Entity\Group as GroupEntity;
use App\Entity\Label as LabelEntity;
use App\Entity\Milestone as MilestoneEntity;
use App\Entity\Project as ProjectEntity;
use App\Entity\Status as StatusEntity;
use App\Entity\Task as TaskEntity;
use App\Entity\Team as TeamEntity;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\EntityManagerInterface;

/**
 * @implements Transformer<TaskResource, TaskEntity>
 */
readonly class ResourceTaskTransformer extends AbstractTransformer implements Transformer
{
    public function __construct(private EntityManagerInterface $entityManager)
    {
    }

    /**
     * @param TaskResource $data
     * @param array{
     *     object_to_populate?: object,
     * } $context
     * @return TaskEntity
     */
    public function transform($data, array $context = []): TaskEntity
    {
        $taskEntity = $context['object_to_populate'] ?? new TaskEntity();
        $taskEntity->title = $data->title;
        $taskEntity->description = $data->description;
        $taskEntity->status = $this->entityManager->find(StatusEntity::class, $data->statusId);
        $taskEntity->project = $this->entityManager->find(ProjectEntity::class, $data->projectId);
        $taskEntity->team = $this->entityManager->find(TeamEntity::class, $data->teamId);
        $taskEntity->cycle = $data->cycleId ? $this->entityManager->find(CycleEntity::class, $data->cycleId) : null;
        $taskEntity->milestone = $data->milestoneId ? $this->entityManager->find(
            MilestoneEntity::class,
            $data->milestoneId
        ) : null;
        $taskEntity->labels = new ArrayCollection(
            array_map(function (string $labelId) {
                return $this->entityManager->find(LabelEntity::class, $labelId);
            }, $data->labelIds)
        );
        $taskEntity->components = new ArrayCollection(
            array_map(function (string $componentId) {
                return $this->entityManager->find(ComponentEntity::class, $componentId);
            }, $data->componentIds)
        );
        $taskEntity->groups = new ArrayCollection(
            array_map(function (string $groupId) {
                return $this->entityManager->find(GroupEntity::class, $groupId);
            }, array_merge($data->teamGroupIds, $data->projectGroupIds))
        );

        return $taskEntity;
    }

    public function supports($data): bool
    {
        return $data instanceof TaskResource;
    }
}