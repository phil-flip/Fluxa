import type {Task} from "$src/api/schema/schema";

export type TaskGroupWithLabelAndGroupId = [string | undefined, string, Task[]];
export type TaskGroupsWithLabelAndGroupId = TaskGroupWithLabelAndGroupId[];
export type TaskSelectorTuple = [undefined | string, Task];
export type SortFunction = (a: TaskGroupWithLabelAndGroupId, b: TaskGroupWithLabelAndGroupId) => number;

export function groupTasksByProperty(tasks: Task[], grouper: TaskGrouper): TaskGroupsWithLabelAndGroupId {
    const groupsMap = tasks.reduce((allGroups, task) => {
        const taskGroups = grouper.select(task);

        taskGroups.forEach(([id, task]: TaskSelectorTuple) => {
            const tasksInGroup = allGroups.get(id) ?? [];
            tasksInGroup.push(task);

            allGroups.set(id, tasksInGroup);
        });

        return allGroups;
    }, new Map<string | undefined, Task[]>());

    return Array.from(groupsMap)
        .map(([id, tasks]): TaskGroupWithLabelAndGroupId => {
            tasks.sort((a, b) => new Date(a.createdAt).getTime() - new Date(b.createdAt).getTime());

            return [id, grouper.getLabel(id), tasks];
        })
        .sort(grouper.sorter ? grouper.sorter.bind(grouper) : undefined);
}

export interface TaskGrouper {
    select: (task: Task) => TaskSelectorTuple[],
    getLabel: (id: string | undefined) => string | null,
    sorter?: SortFunction
}
