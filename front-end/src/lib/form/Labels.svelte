<script lang="ts">
    import Checkbox from "$lib/form/Checkbox.svelte";
    import type {Label, NewLabel} from "$src/api/schema/schema";
    import {api} from "$src/api/ServerApiClient";
    import type {OnCreate} from "$src/lib/form/Choice";

    export let choices: Label[];
    export let clickToShow: boolean = true;
    export let baseComponent: Partial<NewLabel> | undefined = undefined;

    const onCreate: OnCreate<Label> = async (value: string) => {
        return await api.postLabel({
            ...baseComponent,
            name: value
        } as NewLabel);
    }

    const getValue = (choice: Label) => choice.id;
    const getFilterValue = (choice: Label) => choice.name;
    let value: Checkbox<Label>['value'];
</script>

<style lang="scss">
  div {
    display: contents;
  }

  div :global(input[type="checkbox"]:checked ~ span), :global(.labels input[type="checkbox"]:checked ~ span) {
    background-image: url("data:image/svg+xml,%3csvg aria-hidden='true' xmlns='http://www.w3.org/2000/svg' fill='none' viewBox='0 0 16 12'%3e %3cpath stroke='white' stroke-linecap='round' stroke-linejoin='round' stroke-width='3' d='M1 5.917 5.724 10.5 15 1.5'/%3e %3c/svg%3e");
    background-size: 55%;
    background-repeat: no-repeat;
    background-position: center;
  }
</style>

<!-- Check if bind:value is still needed -->
<div>
    <Checkbox {...$$restProps}
              bind:value
              choices="{[...choices].sort((a,b)=>a.name.localeCompare(b.name))}"
              clickToShow="{clickToShow}"
              custom
              dropdownClass="labels"
              getFilterValue="{getFilterValue}"
              getValue="{getValue}"
              let:choice={choice}
              multiple
              name="labelIds"
              on:change
              onCreate="{onCreate}"
              placeholder="Labels">
        <svelte:fragment let:selectedChoices={choices} slot="button">
            {#if choices.length}
                {#each choices as choice}
                <span class="w-2 h-2 rounded-full inline-block"
                      style="background-color: {choice.properties.color}"/>
                {/each}
                <span class="ml-1">{choices.length} labels</span>
            {:else}
                Labels
            {/if}
        </svelte:fragment>

        <span class="w-3 h-3 mr-1 rounded-full"
              style="background-color: {choice.properties.color}"/>
        {choice.name}
    </Checkbox>
</div>
