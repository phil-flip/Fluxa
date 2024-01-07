import type {TaskGrouper, TaskGroupWithLabelAndGroupId, TaskSelectorTuple} from "./TaskGrouper";
import type {Task} from "$src/api/schema/schema";
import type {DataStoreApiClient} from "$src/api/DataStoreApiClient";

export class MilestoneTaskGrouper implements TaskGrouper {
    constructor(private readonly dataStoreApiClient: DataStoreApiClient) {
    }

    getLabel(id: string | undefined): string {
        if (id) {
            return this.dataStoreApiClient.getMilestone(id)!.name;
        }

        return 'No milestone';
    }

    select(task: Task): TaskSelectorTuple[] {
        if (task.milestoneId) {
            return [[task.milestoneId, task]];
        }

        return [[undefined, task]];
    }

    sorter([idA]: TaskGroupWithLabelAndGroupId, [idB]: TaskGroupWithLabelAndGroupId): number {
        if (idA === undefined && idB === undefined) return 0;
        if (idA === undefined) return 1;
        if (idB === undefined) return -1;

        return this.dataStoreApiClient.getMilestone(idA)!.sortOrder - this.dataStoreApiClient.getMilestone(idB)!.sortOrder;
    }

}