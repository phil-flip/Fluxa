<script lang="ts">
    import Radio from "$src/lib/form/Radio.svelte";
    import type {Milestone, NewMilestone} from "$src/api/schema/schema";
    import type {OnCreate} from "$src/lib/form/Choice";
    import {api} from "$src/api/ServerApiClient";

    export let choices: Milestone[];
    export let clickToShow: boolean = true;
    export let baseComponent: Partial<NewMilestone> | undefined = undefined;

    const onCreate: OnCreate<Milestone> = async (value: string) => {
        return await api.postMilestone({
            ...baseComponent,
            name: value
        } as NewMilestone);
    }
    const getValue = (choice: Milestone) => choice.id;
    const getFilterValue = (choice: Milestone) => choice.name;
    let value: Radio<Milestone>['value'];
</script>

<Radio {...$$restProps}
       bind:value
       choices="{[...choices].sort((a,b)=>a.name.localeCompare(b.name))}"
       getFilterValue="{getFilterValue}"
       getValue="{getValue}"
       let:choice={choice}
       name="milestoneId"
       on:change
       onCreate="{onCreate}"
       clickToShow="{clickToShow}"
       enableSearch="{true}">
    <svelte:fragment let:selectedChoice={choice} slot="button">
        {#if choice}
            {choice.name}
        {:else}
            Milestone
        {/if}
    </svelte:fragment>

    {choice.name}
</Radio>