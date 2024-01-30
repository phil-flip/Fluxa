<script lang="ts">
    import Radio from "./Radio.svelte";
    import type {Cycle} from "$src/api/schema/schema";

    export let choices: Cycle[];

    const getValue = (choice: Cycle) => choice.id;
    const getFilterValue = (choice: Cycle) => choice.name;
</script>

<Radio on:change
       choices="{choices}"
       name="cycleId"
       let:choice={choice}
       getValue="{getValue}"
       getFilterValue="{getFilterValue}"
       custom
       {...$$restProps}>
    <svelte:fragment slot="button" let:selectedChoice={choice}>
        {#if choice}
            {choice.name}
        {:else}
            Cycle
        {/if}
    </svelte:fragment>

    {choice.name}
</Radio>
