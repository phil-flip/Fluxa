import type {TaskGrouper, TaskGroupWithLabelAndGroupId, TaskSelectorTuple} from "./TaskGrouper";
import type {Task} from "$src/api/schema/schema";
import type {DataStoreApiClient} from "$src/api/DataStoreApiClient";

export class ProjectTaskGrouper implements TaskGrouper {
    constructor(private readonly dataStoreApiClient: DataStoreApiClient) {
    }

    getLabel(id: string | undefined): string {
        if (id) {
            return this.dataStoreApiClient.getProject(id)!.name;
        }

        return 'No project';
    }

    select(task: Task): TaskSelectorTuple[] {
        if (task.projectId) {
            return [[task.projectId, task]];
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