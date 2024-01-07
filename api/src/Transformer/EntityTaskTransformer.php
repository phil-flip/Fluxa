<?php

namespace App\Transformer;

use App\ApiResource\Task as TaskResource;
use App\Entity\Component;
use App\Entity\Group;
use App\Entity\Label;
use App\Entity\Task as TaskEntity;

/**
 * @implements Transformer<TaskEntity, TaskResource>
 */
readonly class EntityTaskTransformer extends AbstractTransformer implements Transformer
{
    public function transform($data, array $context = []): TaskResource
    {
        $taskResource = new TaskResource();
        $taskResource->id = $data->getId();
        $taskResource->urgency = $data->urgency;
        $taskResource->number = $data->number;
        $taskResource->title = $data->title;
        $taskResource->description = $data->description;
        $taskResource->statusId = $data->status->getId();
        $taskResource->teamId = $data->team->getId();
        $taskResource->projectId = $data->project->getId();
        if ($data->cycle) {
            $taskResource->cycleId = $data->cycle->getId();
        }
        if ($data->assignee) {
            $taskResource->assigneeId = $data->assignee->getId();
        }
        if ($data->milestone) {
            $taskResource->milestoneId = $data->milestone->getId();
        }
        $taskResource->labelIds = $data->labels->map(fn(Label $label) => $label->getId())->toArray();

        [$projectGroups, $teamGroups] = $data->groups->partition(
            fn(int $index, Group $group) => $group->project !== null
        );
        $taskResource->teamGroupIds = $teamGroups->map(fn(Group $group) => $group->getId())->toArray();
        $taskResource->projectGroupIds = $projectGroups->map(fn(Group $group) => $group->getId())->toArray();
        $taskResource->componentIds = $data->components->map(fn(Component $component) => $component->getId())
            ->toArray();

        $taskResource->createdAt = $data->createdAt->format(DATE_ATOM);
        $taskResource->updatedAt = $data->updatedAt?->format(DATE_ATOM);

        return $taskResource;
    }

    public function supports($data): bool
    {
        return $data instanceof TaskEntity;
    }
}