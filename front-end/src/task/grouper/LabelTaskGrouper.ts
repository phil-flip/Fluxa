import type {TaskGrouper, TaskGroupWithLabelAndGroupId, TaskSelectorTuple} from "$src/task/grouper/TaskGrouper";
import type {Task} from "$src/api/schema/schema";
import type {DataStoreApiClient} from "$src/api/DataStoreApiClient";

export class LabelTaskGrouper implements TaskGrouper {
    constructor(private readonly dataStoreApiClient: DataStoreApiClient) {
    }

    getLabel(id: string | undefined): string {
        if (id) {
            return this.dataStoreApiClient.getLabel(id)!.name;
        }

        return 'No label';
    }

    select(task: Task): TaskSelectorTuple[] {
        if (task.labelIds.length) {
            return task.labelIds.reduce((accumulator: TaskSelectorTuple[], labelId: string) => {
                accumulator.push([labelId, task]);

                return accumulator;
            }, []);
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
