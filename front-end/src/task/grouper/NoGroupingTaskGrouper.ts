import type {TaskGrouper, TaskGroupWithLabelAndGroupId, TaskSelectorTuple} from "$src/task/grouper/TaskGrouper";
import type {Task} from "$src/api/schema/schema";

export class NoGroupingTaskGrouper implements TaskGrouper {
    getLabel(id: string | undefined): null {
        return null;
    }

    select(task: Task): TaskSelectorTuple[] {
        return [[undefined, task]];
    }

    sorter([idA, labelA]: TaskGroupWithLabelAndGroupId, [idB, labelB]: TaskGroupWithLabelAndGroupId): number {
        if (idA === undefined && idB === undefined) return 0;
        if (idA === undefined) return 1;
        if (idB === undefined) return -1;

        return labelA.localeCompare(labelB);
    }
}
