<script lang="ts">
    import Radio, {type ValueType} from "$lib/form/Radio.svelte";
    import {Briefcase} from "lucide-svelte";
    import type {OnCreate} from "$src/lib/form/Choice";
    import type {NewProject, Project} from "$src/api/schema/schema";
    import {api} from "$src/api/ServerApiClient";

    export let value: ValueType;
    export let choices: Project[];
    export let baseComponent: Partial<NewProject> | undefined = undefined;

    const onCreate: OnCreate<Project> = async (value: string) => {
        return await api.postProject({
            ...baseComponent,
            name: value
        } as NewProject);
    }
    const getValue = (choice: Project) => choice.id;
    const getFilterValue = (choice: Project) => choice.name;
</script>

<Radio on:change
       choices="{[...choices].sort((a,b)=>a.name.localeCompare(b.name))}"
       name="projectId"
       let:choice={choice}
       custom
       onCreate="{onCreate}"
       getValue="{getValue}"
       getFilterValue="{getFilterValue}"
       bind:value
       {...$$restProps}>
    <svelte:fragment slot="button" let:selectedChoice={choice}>
        {#if choice}
            <Briefcase class="w-3 h-3 mr-1"/>
            {choice.name}
        {:else}
            Project
        {/if}
    </svelte:fragment>

    {choice.name}
</Radio>
