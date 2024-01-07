<script lang="ts">
    import Radio from "./Radio.svelte";
    import type {Status} from "$src/api/schema/schema";

    export let choices: Status[];
    export let clickToShow: boolean = true;

    const getValue = (choice: Status) => choice.id;
    const getFilterValue = (choice: Status) => choice.name;
</script>

<Radio {...$$restProps}
       choices="{[...choices].sort((a,b)=>a.sortOrder-b.sortOrder)}"
       clickToShow="{clickToShow}"
       getFilterValue="{getFilterValue}"
       getValue="{getValue}"
       let:choice={choice}
       name="statusId"
       on:change>
    <svelte:fragment let:selectedChoice={choice} slot="button">
        {#if choice}
            {choice.name}
        {:else}
            Status
        {/if}
    </svelte:fragment>

    {choice.name}
</Radio>
