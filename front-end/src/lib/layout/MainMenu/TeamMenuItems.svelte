<script lang="ts">
    import "$src/app.scss";
    import TasksIcon from "$lib/svg/TasksIcon.svelte";
    import ProjectIcon from "$lib/svg/ProjectIcon.svelte";
    import {dataStoreApiClient} from "$src/api/DataStoreApiClient";
    import {createAccordion, melt} from '@melt-ui/svelte';
    import type {Team} from "$src/api/schema/schema";
    import StateIndicator from "$lib/layout/MainMenu/StateIndicator.svelte";

    const {
        elements: {content, item, trigger, root},
        helpers: {isSelected},
    } = createAccordion();

    export let team: Team;
    $: projectsAccordionId = `team_${team.id}_projects`;
</script>

<style lang="scss">
    .hidden {
        height: 0;
    }
</style>

<ul class="">
    <li>
        <a class="flex items-center gap-x-3.5 py-2 pr-2.5 text-sm text-slate-700 rounded-md hover:bg-gray-100
        dark:bg-gray-800 dark:text-slate-400 dark:hover:text-slate-300"
           href="/teams/{team.slug}/tasks">
            <TasksIcon/>
            Tasks
        </a>
    </li>
    <li use:melt={$item(projectsAccordionId)}>
        <button
            class="group inline-flex items-center
            justify-between gap-x-3 w-full text-left text-gray-800 transition hover:text-gray-500
            dark:text-gray-200 dark:hover:text-gray-400 pr-2.5"
            use:melt={$trigger(projectsAccordionId)}>
            <span
                class="flex items-center gap-x-3.5 py-2 pr-2.5 text-sm text-slate-700 rounded-md dark:bg-gray-800 dark:text-slate-400 dark:hover:text-slate-300">
                <ProjectIcon/>
                Projects
            </span>

            <StateIndicator state="{$isSelected(projectsAccordionId)}"/>
        </button>
        <div class="w-full overflow-hidden slide" use:melt={$content(projectsAccordionId)}>
            <ul class="pl-8 whitespace-nowrap">
                {#each team.projectIds as projectId}
                    {@const project = $dataStoreApiClient.getProject(projectId)}

                    <li class="overflow-ellipsis overflow-hidden">
                        <a class="flex items-center gap-x-3.5 py-2 pr-2.5 text-sm text-slate-700 rounded-md hover:bg-gray-100 dark:bg-gray-800 dark:text-slate-400 dark:hover:text-slate-300"
                           href="/projects/{project.code}">{project.name}</a>
                    </li>
                {/each}
            </ul>
        </div>
    </li>
</ul>
