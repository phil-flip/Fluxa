<script lang="ts">
    import UrgentIcon from "$lib/svg/UrgentIcon.svelte";
    import ComponentIcon from "$lib/svg/ComponentIcon.svelte";
    import GroupIcon from "$lib/svg/GroupIcon.svelte";
    import ProjectIcon from "$lib/svg/ProjectIcon.svelte";
    import {contextMenuData, contextMenuEvent} from "$src/stores/ContextMenuStore";
    import {dataStoreApiClient} from "$src/api/DataStoreApiClient";
    import type {Project, Status, Task, Team} from "$src/api/schema/schema";
    import MilestoneIcon from "$lib/svg/MilestoneIcon.svelte";

    export let task: Task;
    export let i: number;

    $: project = $dataStoreApiClient.getProject(task.projectId) as Project;
    $: team = $dataStoreApiClient.getTeam(task.teamId) as Team;

    let detailPageUrl: string;
    let status: Status;
    $: {
        status = team.workflow.statuses.find(status => status.id === task.statusId) as Status;
        detailPageUrl = `/tasks/${project.code}-${task.number}`;

        console.debug('Reactive statement triggered');
    }
</script>

<style lang="scss">
  .row {
    display: contents;

    .cell {
      border-bottom-width: 1px;
      border-style: solid;
      border-color: #F0F0F0;
    }

    &:hover .cell {
      background-color: #FAFAFA;
    }

    &:focus-within .cell {
      border-top-width: 1px;
      border-color: rgba(136, 213, 236, 0.63);
      background-color: #FAFAFA;
    }

    &:has(+ :focus-within) .cell {
      border-bottom-width: 0;
    }
  }

  .cell {
    padding: 0.5rem 0.25rem;
    white-space: nowrap;
    display: flex;
    align-items: center;
  }

  .cell.main {
    display: flex;
    justify-content: space-between;
    gap: 1rem;
  }

  .cell.assignee {
    display: flex;
    flex-direction: row;
    column-gap: .5rem;
  }

  .cell.assignee img {
    max-width: none;
  }

  .label-shrink-wrapper {
    display: flex;
    align-items: center;
    /*flex-shrink: 1;*/
    min-width: 1rem;
    /*overflow: visible;*/

    /* Prevent big labels making the view noisy */
    /*max-width: 3rem;*/
    /*overflow:hidden;*/
    /*text-overflow: ellipsis;*/
    transition: flex-shrink 0.25s cubic-bezier(0.38, 0.01, 0.33, 1) 0s;
  }

  .label-shrink-wrapper:hover {
    flex-shrink: 0;
  }

  .label-shrink-wrapper * {
    background: white;
  }

  .status > * {
    width: 0.25rem;
  }

  .status .status-1 {
    height: 0.3rem;
  }

  .status .status-2 {
    height: 0.6rem;
  }

  .status .status-3 {
    height: 0.9rem;
  }
</style>

<div aria-haspopup="menu"
     aria-rowindex="{i}"
     class="row"
     on:contextmenu={(event)=>{
        event.preventDefault();

        $contextMenuData = {taskId: task.id};
        $contextMenuEvent = event;
    }}
     role="row"
     tabindex="-1">
    <div class="cell checkbox">
        <label class="flex" for="{task.id}">
            <input class="shrink-0 border-gray-200 rounded text-blue-600 pointer-events-none focus:ring-blue-500 dark:bg-gray-800 dark:border-gray-700 dark:checked:bg-blue-500 dark:checked:border-blue-500 dark:focus:ring-offset-gray-800"
                   id="{task.id}"
                   tabindex="-1"
                   type="checkbox">
            <span class="sr-only">Checkbox</span>
        </label>
    </div>
    <div class="cell" tabindex="-1">
        {#if task.urgency !== undefined }
            {#if task.urgency < 3}
                <div class="status">
                    {#each {length: task.urgency + 1} as _, height}
                        <span class="inline-block status-{height+1} bg-gray-500 rounded-full mr-0.5"></span>
                    {/each}
                </div>
            {:else}
                <UrgentIcon/>
            {/if}
        {/if}
    </div>
    <a class="cell" href="{detailPageUrl}" role="gridcell" tabindex="-1">
        {project.code}-{task.number}
    </a>
    <div class="cell">
        {#if status}
            {#if status.name === 'Done'}
                <span
                    class="inline-flex items-center gap-1.5 py-0.5 px-2 rounded-full text-xs bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200">
                    <svg class="w-2.5 h-2.5" xmlns="http://www.w3.org/2000/svg" width="16"
                         height="16"
                         fill="currentColor" viewBox="0 0 16 16">
                        <path
                            d="M16 8A8 8 0 1 1 0 8a8 8 0 0 1 16 0zm-3.97-3.03a.75.75 0 0 0-1.08.022L7.477 9.417 5.384 7.323a.75.75 0 0 0-1.06 1.06L6.97 11.03a.75.75 0 0 0 1.079-.02l3.992-4.99a.75.75 0 0 0-.01-1.05z"/>
                    </svg>
                    {status.name}
                </span>
            {:else if status.name === 'In progress'}
                <span
                    class="inline-flex items-center gap-1.5 py-0.5 px-2 rounded-full text-xs bg-yellow-100 text-yellow-900 dark:bg-gray-900 dark:text-gray-200">
                    {status.name}
                </span>
            {:else}
                <span
                    class="inline-flex items-center gap-1.5 py-0.5 px-2 rounded-full text-xs bg-gray-100 text-gray-800 dark:bg-gray-900 dark:text-gray-200">
                    {status.name}
                </span>
            {/if}
        {/if}
    </div>
    <a class="cell main truncate" href="{detailPageUrl}">
        <div class="text-sm text-gray-700 dark:text-gray-400 truncate shrink-[1]">
            {task.title}
        </div>
        <div class="flex text-gray-500 dark:text-gray-400 text-xs overflow-hidden shrink-[2]">
            {#each task.labelIds || [] as labelId}
                {@const label = $dataStoreApiClient.getLabel(labelId)}

                <div class="label-shrink-wrapper">
                        <span class="inline-flex gap-1.5 py-1 px-2 rounded-full border items-center bg-white">
                            <span class="inline-block w-2 h-2 rounded-full"
                                  style="background-color: {label.properties.color}"></span>
                            {label.name}
                        </span>
                </div>
            {/each}
            <div class="label-shrink-wrapper">
                    <span class="inline-flex gap-1.5 py-1 px-2 rounded-full border items-center bg-white"
                          title="Project">
                        <ProjectIcon/>
                        {project.name}
                    </span>
            </div>

            {#if task.milestoneId}
                {@const milestone = $dataStoreApiClient.getMilestone(task.milestoneId)}

                <div class="label-shrink-wrapper">
                        <span title="Milestone"
                              class="inline-flex gap-1.5 py-1 px-2 rounded-full border items-center bg-white">
                            <MilestoneIcon/>
                            {milestone.name}
                        </span>
                </div>
            {/if}

            {#if (task.projectGroupIds || []).length }
                <div class="label-shrink-wrapper">
                        <span title="Component"
                              class="inline-flex gap-1.5 py-1 px-2 rounded-full border items-center bg-white">
                            <GroupIcon/>
                            {#each task.projectGroupIds || [] as groupId, i}
                                {@const group = $dataStoreApiClient.getGroup(groupId)}

                                {#if i > 0},{/if}
                                {group.name}
                            {/each}
                        </span>
                </div>
            {/if}
            {#if (task.componentIds || []).length }
                <div class="label-shrink-wrapper">
                        <span title="Groups"
                              class="inline-flex gap-1.5 py-1 px-2 rounded-full border items-center bg-white">
                            <ComponentIcon/>
                            {#each task.componentIds || [] as componentId, i}
                                {@const component = $dataStoreApiClient.getComponent(componentId)}

                                {#if i > 0},{/if}
                                {component.name}
                            {/each}
                        </span>
                </div>
            {/if}

            {#if task.cycleId}
                {@const cycle = $dataStoreApiClient.getCycle(task.cycleId)}

                <div class="label-shrink-wrapper">
                        <span class="inline-flex gap-1.5 py-1 px-2 rounded-full border items-center"
                              title="Cycle">
                            <svg
                                    class="inline svg-icon fill-gray-500"
                                    style="overflow:hidden;"
                                    viewBox="0 0 613.37598 614.40002"
                                    version="1.1"
                                    width="12"
                                    height="12"
                                    xmlns="http://www.w3.org/2000/svg"
                            >
                                <defs/>
                                <path
                                        d="m 578.56,392.192 c 0,1.024 0,2.048 -1.024,2.048 -3.072,11.264 -7.168,22.528 -11.264,33.792 -5.12,12.288 -10.24,23.552 -16.384,34.816 -12.288,22.528 -27.648,43.008 -46.08,61.44 -17.408,18.432 -37.888,34.816 -60.416,48.128 -22.528,13.312 -46.08,23.552 -71.68,30.72 -25.6,7.168 -52.224,11.264 -78.848,11.264 C 131.072,614.4 0,483.328 0,320.512 0,157.696 131.072,27.648 292.864,27.648 c 54.272,0 105.472,15.36 149.504,40.96 h 1.024 c 21.504,12.288 40.96,27.648 57.344,45.056 5.12,4.096 8.192,7.168 12.288,12.288 9.216,8.192 16.384,3.072 16.384,-9.216 V 20.48 C 529.408,9.216 538.624,0 549.888,0 h 40.96 c 11.264,0 20.48,9.216 22.528,20.48 v 250.88 c 0,9.216 -8.192,18.432 -18.432,18.432 h -250.88 c -11.264,0 -19.456,-8.192 -19.456,-19.456 v -43.008 c 0,-11.264 9.216,-20.48 20.48,-20.48 h 96.256 c 8.192,0 14.336,-2.048 17.408,-7.168 -36.864,-51.2 -98.304,-84.992 -165.888,-84.992 -113.664,0 -205.824,92.16 -205.824,205.824 0,113.664 92.16,205.824 205.824,205.824 89.088,0 164.864,-56.32 193.536,-136.192 0,0 3.072,-18.432 17.408,-18.432 h 58.368 c 8.192,0 16.384,6.144 16.384,15.36 z"
                                        id="path1"/>
                            </svg>
                            {cycle.name}
                        </span>
                </div>
            {/if}
        </div>
    </a>
    <div class="cell assignee">
        {#if task.assigneeId}
            <img class="h-6 w-6 rounded-full"
                 src="https://images.unsplash.com/photo-1568602471122-7832951cc4c5?ixlib=rb-4.0.3&amp;ixid=MnwxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8&amp;auto=format&amp;fit=facearea&amp;facepad=2&amp;w=300&amp;h=300&amp;q=80"
                 alt="Nick Stemerdink">
            <!--                <span class="text-sm text-gray-600 dark:text-gray-400">{task.assignee.name}</span>-->
        {/if}
    </div>
</div>
