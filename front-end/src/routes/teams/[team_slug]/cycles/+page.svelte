<script lang="ts">
    import Header from "$lib/layout/Header.svelte";
    import {GROUPS} from "$src/stores/ContextStore";
    import {LAYOUTS} from "$src/stores/ContextStore";
    import TaskLists from "$lib/tasks/TaskLists.svelte";
    import Main from "$lib/layout/Main.svelte";
    import {dataStore} from "$src/stores/DataStore";
    import {page} from "$app/stores";
    import {dataStoreApiClient} from "$src/api/DataStoreApiClient";

    export let data;

    $: teamId = $dataStoreApiClient.resolveTeamSlug($page.params.team_slug);
    $: tasks = $dataStore.tasks.filter(task => {
        return task.teamId === teamId
    });
</script>

<Header breadcrumb="Team Fluxa - Cycles"/>

<Main>
    <TaskLists groupBy="{GROUPS.CYCLE}" layout="{LAYOUTS.LIST}" tasks="{tasks}"/>
</Main>
