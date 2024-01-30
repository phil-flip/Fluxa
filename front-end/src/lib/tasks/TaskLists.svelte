<script lang="ts">
    import TaskList from "$lib/tasks/TaskList.svelte";
    import {groupTasksByProperty} from "$src/task/grouper/TaskGrouper";
    import {MilestoneTaskGrouper} from "$src/task/grouper/MilestoneTaskGrouper";
    import type {Task} from "$src/api/schema/schema";
    import {GroupTaskGrouper} from "$src/task/grouper/GroupTaskGrouper";
    import {CycleTaskGrouper} from "$src/task/grouper/CycleTaskGrouper";
    import {ProjectTaskGrouper} from "$src/task/grouper/ProjectTaskGrouper";
    import {StatusTaskGrouper} from "$src/task/grouper/StatusTaskGrouper";
    import {AssigneeTaskGrouper} from "$src/task/grouper/AssigneeTaskGrouper";
    import {ComponentTaskGrouper} from "$src/task/grouper/ComponentTaskGrouper";
    import {LabelTaskGrouper} from "$src/task/grouper/LabelTaskGrouper";
    import {context, GROUPS} from "$src/stores/ContextStore";
    import {dataStoreApiClient} from "$src/api/DataStoreApiClient";
    import {dataStore} from "$src/stores/DataStore";
    import CenterBox from "../CenterBox.svelte";

    export let tasks: Task[];
    export let groupBy: GROUPS | undefined = undefined;

    function getGroups(tasks: Task[], groupBy: GROUPS) {
        switch (groupBy) {
            case GROUPS.MILESTONE:
                return groupTasksByProperty(tasks, new MilestoneTaskGrouper($dataStoreApiClient));
            case GROUPS.COMPONENT:
                return groupTasksByProperty(tasks, new ComponentTaskGrouper($dataStoreApiClient));
            case GROUPS.CYCLE:
                return groupTasksByProperty(tasks, new CycleTaskGrouper($dataStoreApiClient));
            case GROUPS.STATUS:
                return groupTasksByProperty(tasks, new StatusTaskGrouper($dataStore.teams ?? []));
            case GROUPS.PROJECT:
                return groupTasksByProperty(tasks, new ProjectTaskGrouper($dataStoreApiClient));
            case GROUPS.ASSIGNEE:
                return groupTasksByProperty(tasks, new AssigneeTaskGrouper($dataStoreApiClient));
            case GROUPS.LABEL:
                return groupTasksByProperty(tasks, new LabelTaskGrouper($dataStoreApiClient));
            case GROUPS.GROUP:
                return groupTasksByProperty(tasks, new GroupTaskGrouper($dataStoreApiClient));
            default:
                throw new Error(`Unknown group by: ${groupBy}`);
        }
    }

    $: groupedTasks = getGroups(tasks, groupBy ?? $context.groupBy);
</script>

<style lang="scss">
    .layout.swimlanes {
        display: flex;

        :global(.grid-table) {
        }
    }
</style>


{#if groupedTasks.length === 0}
    <CenterBox>
        <h2>No tasks</h2>
        <p>There are no tasks here yet. Create your first task now and it will show up here.</p>
    </CenterBox>
{:else}
    <div class="layout {$context.layout}">
        {#each groupedTasks as [_id, title, tasks]}
            <TaskList tasks="{tasks}" title="{title}"/>
        {/each}
    </div>
{/if}
