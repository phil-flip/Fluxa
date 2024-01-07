import type {TaskGrouper, TaskGroupWithLabelAndGroupId, TaskSelectorTuple} from "./TaskGrouper";
import type {Status, Task, Team} from "$src/api/schema/schema";

export class StatusTaskGrouper implements TaskGrouper {
    private statuses: Map<string, Status>;

    constructor(readonly teams: Team[]) {
        this.statuses = new Map(
            teams.flatMap(team => team.workflow.statuses)
                .map(status => [status.id, status])
        );
    }

    getLabel(id: string | undefined): string {
        if (id) {
            return this.statuses.get(id)!.name;
        }

        return 'No status';
    }

    select(task: Task): TaskSelectorTuple[] {
        if (task.statusId) {
            return [[task.statusId, task]];
        }

        return [[undefined, task]];
    }

    sorter([idA, labelA]: TaskGroupWithLabelAndGroupId, [idB, labelB]: TaskGroupWithLabelAndGroupId): number {
        if (idA === undefined && idB === undefined) return 0;
        if (idA === undefined) return 1;
        if (idB === undefined) return -1;

        return labelA.localeCompare(labelB);
    }
}
