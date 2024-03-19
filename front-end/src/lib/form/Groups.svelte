<script lang="ts">
    import Checkbox, {type ValueType} from "$lib/form/Checkbox.svelte";
    import type {Group, NewGroup} from "$src/api/schema/schema";
    import type {OnCreate} from "$src/lib/form/Choice";
    import {api} from "$src/api/ServerApiClient";

    export let choices: Group[];
    export let clickToShow: boolean = true;
    export let baseComponent: Partial<NewGroup> | undefined = undefined;
    export let label: string = 'Groups';
    export let value: ValueType;

    const onCreate: OnCreate<Group> = async (value: string) => {
        return await api.postGroup({
            ...baseComponent,
            name: value
        });
    }

    const getValue = (choice: Group) => choice.id;
    const getFilterValue = (choice: Group) => choice.name;
</script>

<Checkbox
    name="groupIds"
    bind:checkboxGroup={value}
    {...$$restProps}
    choices="{[...choices].sort((a,b)=>a.name.localeCompare(b.name))}"
    clickToShow="{clickToShow}"
    getFilterValue="{getFilterValue}"
    getValue="{getValue}"
    let:choice={choice}
    on:change
    onCreate="{onCreate}">
    <svelte:fragment let:selectedChoices={choices} slot="button">
        {#if choices.length}
            <span class="ml-1">{choices.length} {label.toLowerCase()}</span>
        {:else}
            {label}
        {/if}
    </svelte:fragment>

    {choice.name}
</Checkbox>
