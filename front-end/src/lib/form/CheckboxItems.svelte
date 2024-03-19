<style lang="scss">
    li.choice:focus-within {
        outline: 2px solid;
    }

    label {
        display: flex;
        align-items: center;
        padding: 0.5rem;
        font-size: 0.875rem;
        line-height: 1.25rem;
        font-weight: 500;
        color: rgb(17 24 39 / var(--tw-text-opacity));
    }

    input {
        box-sizing: border-box;
        border-style: solid;
        margin: 0;
        appearance: none;
        padding: 0;
        display: inline-block;
        vertical-align: middle;
        background-origin: border-box;
        flex-shrink: 0;
        border-width: 1px;
        margin-inline-end: 0.5rem;
        height: 1rem;
        width: 1rem;
        border-radius: 0.25rem;
        --tw-border-opacity: 1;
        border-color: rgb(209 213 219 / var(--tw-border-opacity));
        --tw-bg-opacity: 1;
        background-color: rgb(243 244 246 / var(--tw-bg-opacity));
        --tw-text-opacity: 1;
        color: rgb(56 77 239 / var(--tw-text-opacity));
    }
</style>

<script generics="Choice" lang="ts">
    import type {GetValueType} from "$lib/form/Choice";
    import {Plus} from "lucide-svelte";
    import type {ValueType} from "$lib/form/Checkbox.svelte";

    export let searchQuery: string;
    export let filteredChoices: Choice[];
    export let internalOnCreate: (event: Event) => void;
    export let getValue: GetValueType<Choice>;
    export let checkboxGroup: ValueType;
    export let onChange: (event: Event & { currentTarget: EventTarget & HTMLInputElement }) => void;
</script>

{#if searchQuery && filteredChoices.length === 0}
    <li class="choice rounded hover:bg-gray-100 dark:hover:bg-gray-600 outline-primary-500">
        <label>
            <input type="checkbox" value="placeholder" on:change={internalOnCreate}/>
            <Plus strokeWidth="3" style="width: 20px;height: 20px;margin-right: .3rem"/>
            {searchQuery}
        </label>
    </li>
{/if}
{#each filteredChoices as choice}
    {@const value = getValue(choice)}
    <li class="choice rounded hover:bg-gray-100 dark:hover:bg-gray-600 outline-primary-500">
        <label>
            <input type="checkbox" value="{value}" checked={checkboxGroup.includes(value)}
                   on:change={onChange}/>
            <slot choice={choice}/>
        </label>
    </li>
{/each}
