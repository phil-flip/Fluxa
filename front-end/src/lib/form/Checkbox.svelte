<script generics="Choice" lang="ts">
    import type {FilterType, GetFilterValue, GetValueType, OnCreate} from "$lib/form/Choice";
    import {ChoiceHandler, ChoicesFilter} from "$lib/form/Choice";
    import {Button, Checkbox, Dropdown, Search} from "flowbite-svelte";
    import {createEventDispatcher} from "svelte";
    import {Plus} from "lucide-svelte";

    // eslint-disable-next-line @typescript-eslint/no-unused-vars
    interface $$Slots {
        default: { choice: Choice },
        button: { selectedChoices: Choice[] };
    }

    type ValueType = (string | number)[];

    export let clickToShow: boolean = true;
    export let value: ValueType = [];
    export let name: string;
    export let choices: Choice[];
    export let getValue: GetValueType<Choice>;
    export let filter: FilterType<Choice> | null = null;
    export let getFilterValue: GetFilterValue<Choice> | null = null;
    export let onCreate: OnCreate<Choice> | undefined = undefined;
    export let dropdownClass = '';

    const dispatch = createEventDispatcher();

    let pendingChangeEventForCreatedChoice = false;

    async function internalOnCreate(event: Event) {
        if (!onCreate) {
            throw new Error('No onCreate callback provided.');
        }

        event.stopPropagation();

        const newChoice = await onCreate(searchQuery);

        pendingChangeEventForCreatedChoice = true;

        // Use assignment (instead of push) to make sure reactive statements are triggered
        choices = [...choices, newChoice];
        checkboxGroup = [...checkboxGroup, getValue(newChoice)];
    }

    function onChange(event: Event) {
        event.stopPropagation();
        dispatchChangeEvent();
    }

    function dispatchChangeEvent() {
        dispatch('change', {
            values: selectedChoices.map(choice => getValue(choice)),
            choices: selectedChoices,
        });

        console.debug('Change event dispatched');
    }

    // TODO: This is not called anymore, why NOT?
    //     $: onChangeSelectedChoices(selectedChoices);
    // So selectedChoices is not updated?
    // This is also applied to the radio
    function onChangeSelectedChoices(selectedChoices: Choice[]) {
        console.debug('onChangeSelectedChoices called with:');
        console.debug(selectedChoices);

        if (pendingChangeEventForCreatedChoice) {
            console.debug('pendingChangeEventForCreatedChoice');

            pendingChangeEventForCreatedChoice = false;
            dispatchChangeEvent();
        }
    }

    function getSelectedChoices(checkboxGroup: ValueType): Choice[] {
        return checkboxGroup.map(value => (choiceHandler.choicesMap.get(value.toString()) as Choice))
            // Filter out undefined. This can happen in case of data corruption, but we don't want to fail the application on that.
            .filter(value => value !== undefined);
    }

    let searchQuery = '';
    let open: boolean;
    let checkboxGroup: ValueType = value;

    $: filterHandler = new ChoicesFilter<Choice>(filter, getFilterValue);
    $: choiceHandler = new ChoiceHandler<Choice>(choices, getValue);
    $: filteredChoices = filterHandler.getFilteredChoices(choices, searchQuery);
    $: selectedChoices = getSelectedChoices(checkboxGroup);

    $: onChangeSelectedChoices(selectedChoices);
</script>

<style lang="scss">
  li.choice:focus-within {
    outline: 2px solid;
  }
</style>

{#if clickToShow}
    <Button class="button whitespace-nowrap" color="alternative" size="xs">
        <slot name="button" selectedChoices="{selectedChoices}"></slot>
    </Button>
    <Dropdown bind:open class="{dropdownClass} overflow-y-auto px-3 pb-3 text-sm h-44" tabindex={-2}>
        <!-- Not possible yet, see: https://github.com/sveltejs/svelte/issues/5604 -->
        <!--{#if filterHandler.hasFilter()}-->
        <div class="p-3" slot="header">
            <Search autofocus bind:value={searchQuery} size="md"/>
        </div>
        <!--{/if}-->
        {#if searchQuery && filteredChoices.length === 0}
            <li class="choice rounded hover:bg-gray-100 dark:hover:bg-gray-600 outline-primary-500">
                <Checkbox on:change={internalOnCreate} class="p-2" value="placeholder">
                    <Plus strokeWidth="3" style="width: 20px;height: 20px;margin-right: .3rem"/>
                    {searchQuery}
                </Checkbox>
            </li>
        {/if}
        {#each filteredChoices as choice}
            <li class="choice rounded hover:bg-gray-100 dark:hover:bg-gray-600 outline-primary-500">
                <Checkbox on:change={onChange} bind:group={checkboxGroup} value="{getValue(choice)}" {...$$restProps}
                          class="p-2">
                    <slot choice={choice}/>
                </Checkbox>
            </li>
        {/each}
    </Dropdown>
{:else}
    <div class="p-3">
        <Search autofocus bind:value={searchQuery} size="md"/>
    </div>
    <ul>
        {#if searchQuery && filteredChoices.length === 0}
            <li class="choice rounded hover:bg-gray-100 dark:hover:bg-gray-600 outline-primary-500">
                <Checkbox on:change={internalOnCreate} class="p-2" value="placeholder">
                    <Plus strokeWidth="3" style="width: 20px;height: 20px;margin-right: .3rem"/>
                    {searchQuery}
                </Checkbox>
            </li>
        {/if}
        {#each filteredChoices as choice}
            <li class="choice rounded hover:bg-gray-100 dark:hover:bg-gray-600 outline-primary-500">
                <Checkbox on:change={onChange} bind:group={checkboxGroup} value="{getValue(choice)}" {...$$restProps}
                          class="p-2">
                    <slot choice={choice}/>
                </Checkbox>
            </li>
        {/each}
    </ul>
{/if}

<input name="{`${name}{}`}" type="hidden" value="{JSON.stringify(checkboxGroup)}"/>
