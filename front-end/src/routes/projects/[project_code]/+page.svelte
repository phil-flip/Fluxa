<script lang="ts">
    import Header from "$lib/layout/Header.svelte";
    import RightSideBar from "$lib/layout/RightSideBar.svelte";
    import RightSideBarItem from "$lib/layout/RightSideBarItem.svelte";
    import TaskLists from "$lib/tasks/TaskLists.svelte";
    import Main from "$lib/layout/Main.svelte";
    import {dataStore} from "$src/stores/DataStore";
    import {page} from "$app/stores";
    import type {Component, Group, Label, Milestone, Project, Task} from "$src/api/schema/schema";
    import {dataStoreApiClient} from "$src/api/DataStoreApiClient";
    import type {GROUPS} from "$src/stores/ContextStore";

    export let data;

    let project: Project;
    let tasks: Task[] = [];
    let milestones: Milestone[] = [];
    let components: Component[] = [];
    let projectGroups: Group[] = [];
    let labels: Label[] = [];

    $: if ($dataStoreApiClient.ready) {
        const projectId = $dataStoreApiClient.resolveProjectCode($page.params.project_code);
        project = $dataStoreApiClient.getProject(projectId);
        tasks = $dataStore.tasks.filter(task => task.projectId === projectId);

        milestones = project.milestoneIds.map(id => $dataStoreApiClient.getMilestone(id));
        components = project.componentIds.map(id => $dataStoreApiClient.getComponent(id));
        projectGroups = project.groupIds.map(id => $dataStoreApiClient.getGroup(id));
        labels = project.labelIds.map(id => $dataStoreApiClient.getLabel(id));
    }
</script>

<Header breadcrumb="Project {project?.name || ''}"/>

<Main>
    <TaskLists tasks="{tasks}"/>
    <RightSideBar>
        <!--        <RightSideBarItem index="1" name="Properties">-->
        <!--            -->
        <!--        </RightSideBarItem>-->
        <RightSideBarItem index="2" name="Groups">
            {#each projectGroups as group, i}
                <div class="py-1">{group.name}</div>
            {/each}
        </RightSideBarItem>
        <RightSideBarItem index="3" name="Components">
            {#each components as component, i}
                <div class="py-1">{component.name}</div>
            {/each}
        </RightSideBarItem>
        <RightSideBarItem index="4" name="Milestones">
            {#each milestones as milestone, i}
                <div class="py-1">{milestone.name}</div>
            {/each}
        </RightSideBarItem>
    </RightSideBar>
</Main>
