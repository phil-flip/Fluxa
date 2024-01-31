import type {TaskGrouper, TaskGroupWithLabelAndGroupId, TaskSelectorTuple} from "$src/task/grouper/TaskGrouper";
import type {Task} from "$src/api/schema/schema";
import type {DataStoreApiClient} from "$src/api/DataStoreApiClient";

export class GroupTaskGrouper implements TaskGrouper {
    constructor(private readonly dataStoreApiClient: DataStoreApiClient) {
    }

    getLabel(id: string | undefined): string {
        if (id) {
            return this.dataStoreApiClient.getGroup(id)!.name;
        }

        return 'No group';
    }

    select(task: Task): TaskSelectorTuple[] {
        if (task.projectGroupIds.length) {
            return task.projectGroupIds.reduce((accumulator: TaskSelectorTuple[], groupId: string) => {
                accumulator.push([groupId, task]);

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
