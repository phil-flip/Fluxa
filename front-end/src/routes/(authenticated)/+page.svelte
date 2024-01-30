<script>
    import Main from "../../lib/layout/Main.svelte";
    import CenterBox from "../../lib/CenterBox.svelte";
    import {dataStore} from "../../stores/DataStore";
</script>

<Main>
    {#if $dataStore.loading === false && $dataStore.teams?.length === 0}
        <CenterBox>
            <h2>No teams</h2>
            <p>Create your first team directly from the "Add task" dialog.</p>
        </CenterBox>
    {/if}
</Main>
