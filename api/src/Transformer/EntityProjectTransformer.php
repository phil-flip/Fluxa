<?php

namespace App\Transformer;

use App\ApiResource\Project as Output;
use App\Entity\Component;
use App\Entity\Group;
use App\Entity\Label;
use App\Entity\Milestone;
use App\Entity\Project as Input;
use App\Entity\Team;

/**
 * @implements Transformer<Input, Output>
 */
readonly class EntityProjectTransformer extends AbstractTransformer implements Transformer
{
    public function transform($data, array $context = []): Output
    {
        $resource = new Output();
        $resource->id = $data->getId();
        $resource->name = $data->name;
        $resource->code = $data->code;
        $resource->componentIds = $data->components->map(fn(Component $component) => $component->getId())->toArray();
        $resource->milestoneIds = $data->milestones->map(fn(Milestone $milestone) => $milestone->getId())->toArray();
        $resource->teamIds = $data->teams
            ->map(fn(Team $team) => $team->getId())
            ->toArray();
        $resource->labelIds = $data->labels
            ->filter(fn(Label $label) => $label->project !== null)
            ->map(fn(Label $label) => $label->getId())
            ->toArray();
        $resource->groupIds = $data->groups
            ->filter(fn(Group $group) => $group->project !== null)
            ->map(fn(Group $group) => $group->getId())
            ->toArray();

        return $resource;
    }

    public function supports($data): bool
    {
        return $data instanceof Input;
    }
}
