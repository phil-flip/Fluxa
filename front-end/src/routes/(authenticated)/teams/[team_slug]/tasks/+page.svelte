<script lang="ts">
    import Header from "$lib/layout/Header.svelte";
    import TaskLists from "$lib/tasks/TaskLists.svelte";
    import {context} from "$src/stores/ContextStore";
    import Main from "$lib/layout/Main.svelte";
    import type {DataStore} from "$src/stores/DataStore";
    import {dataStore} from "$src/stores/DataStore";
    import {page} from "$app/stores";
    import {dataStoreApiClient} from "$src/api/DataStoreApiClient";
    import type {Task} from "$src/api/schema/schema";
    import {browser} from '$app/environment';

    function updateContext(dataStore: DataStore, teamSlug: string) {
        if (browser && !dataStore.loading && !dataStore.error) {
            context.update(context => {
                const team = dataStore.teams.find(team => team.slug === teamSlug);
                const projectId = team?.projectIds?.[0];

                return {
                    ...context,
                    teamId: team?.id,
                    projectId: projectId,
                };
            });
        }
    }

    let teamId: string;
    let tasks: Task[] = [];

    $: if ($dataStoreApiClient.ready) {
        teamId = $dataStoreApiClient.resolveTeamSlug($page.params.team_slug);
        tasks = $dataStore.tasks.filter(task => task.teamId === teamId);
    }
    $: team = teamId ? $dataStoreApiClient.getTeam(teamId) : undefined;

    $: updateContext($dataStore, $page.params.team_slug);
</script>

<Header breadcrumb="Team {team?.name ?? ''} - Tasks"/>

<Main>
    <TaskLists tasks="{tasks}"/>
</Main>
