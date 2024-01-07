<?php

namespace App\Transformer;

use App\ApiResource\Team as TeamResource;
use App\Entity\Cycle;
use App\Entity\Group;
use App\Entity\Label;
use App\Entity\Project;
use App\Entity\Team as TeamEntity;

/**
 * @implements Transformer<TeamEntity, TeamResource>
 */
readonly class EntityTeamTransformer extends AbstractTransformer implements Transformer
{
    public function transform($data, array $context = []): TeamResource
    {
        $resource = new TeamResource();
        $resource->id = $data->getId();
        $resource->name = $data->name;
        $resource->slug = $data->slug;
        $resource->properties = $data->properties;
        $resource->workflow = $this->transformerChain->transform($data->workflow);
        $resource->cycleIds = $data->cycles->map(fn(Cycle $cycle) => $cycle->getId())->toArray();
        $resource->projectIds = $data->projects->map(fn(Project $project) => $project->getId())->toArray();
        $resource->labelIds = $data->labels
            ->filter(fn(Label $label) => $label->team !== null)
            ->map(fn(Label $label) => $label->getId())
            ->toArray();
        $resource->groupIds = $data->groups
            ->filter(fn(Group $group) => $group->team !== null)
            ->map(fn(Group $group) => $group->getId())
            ->toArray();

        return $resource;
    }

    public function supports($data): bool
    {
        return $data instanceof TeamEntity;
    }
}