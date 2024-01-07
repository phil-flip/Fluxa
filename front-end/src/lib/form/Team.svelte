<script lang="ts">
    import Radio from "./Radio.svelte";
    import type {Team} from "$src/api/schema/schema";

    export let value = '';
    export let choices: Team[];

    const getValue = (choice: Team) => choice.id;
    const getFilterValue = (choice: Team) => choice.name;
</script>

<Radio on:change choices="{choices}" name="teamId" let:choice={choice} getValue="{getValue}" getFilterValue="{getFilterValue}"
        custom bind:value {...$$restProps}>
    <svelte:fragment slot="button" let:selectedChoice={choice}>
        {#if choice}
            <span class="w-3 h-3 mr-1 rounded-full inline-block"
                  style="background-color: {choice.properties.color}"/>
            {choice.name}
        {:else}
            Team
        {/if}
    </svelte:fragment>

    <span class="w-3 h-3 mr-1 rounded-full" style="background-color: {choice.properties.color}"/>
    {choice.name}
</Radio>
