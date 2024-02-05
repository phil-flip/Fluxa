<script lang="ts">
    import Checkbox, {type ValueType} from "$lib/form/Checkbox.svelte";
    import type {Component, NewComponent} from "$src/api/schema/schema";
    import {api} from "$src/api/ServerApiClient";
    import type {OnCreate} from "$src/lib/form/Choice";

    export let choices: Component[];
    export let clickToShow: boolean = true;
    export let baseComponent: Partial<NewComponent> | undefined = undefined;
    export let value: ValueType;

    const onCreate: OnCreate<Component> = async (value: string) => {
        return await api.postComponent({
            ...baseComponent,
            name: value
        });
    }

    const getValue = (choice: Component) => choice.id;
    const getFilterValue = (choice: Component) => choice.name;
</script>

<Checkbox {...$$restProps}
          bind:checkboxGroup={value}
          choices="{[...choices].sort((a,b)=>a.name.localeCompare(b.name))}"
          clickToShow="{clickToShow}"
          getFilterValue="{getFilterValue}"
          getValue="{getValue}"
          onCreate="{onCreate}"
          let:choice={choice}
          multiple
          name="componentIds"
          on:change
          placeholder="Labels">
    <svelte:fragment let:selectedChoices={choices} slot="button">
        {#if choices.length}
            <span class="ml-1">{choices.length} components</span>
        {:else}
            Components
        {/if}
    </svelte:fragment>

    {choice.name}
</Checkbox>
