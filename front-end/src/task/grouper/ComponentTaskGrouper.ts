import type {TaskGrouper, TaskGroupWithLabelAndGroupId, TaskSelectorTuple} from "$src/task/grouper/TaskGrouper";
import type {Task} from "$src/api/schema/schema";
import type {DataStoreApiClient} from "$src/api/DataStoreApiClient";

export class ComponentTaskGrouper implements TaskGrouper {
    constructor(private readonly dataStoreApiClient: DataStoreApiClient) {
    }

    getLabel(id: string | undefined): string {
        if (id) {
            return this.dataStoreApiClient.getComponent(id)!.name;
        }

        return 'No component';
    }

    select(task: Task): TaskSelectorTuple[] {
        if (task.componentIds.length) {
            return task.componentIds.reduce((accumulator: TaskSelectorTuple[], componentId: string) => {
                accumulator.push([componentId, task]);

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
