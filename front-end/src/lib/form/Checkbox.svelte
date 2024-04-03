<script lang="ts" context="module">
    export type ValueType = (string | number)[];
</script>

<script generics="Choice" lang="ts">
    import {
        type FilterType,
        type GetFilterValue,
        type GetValueType,
        type OnCreate,
        onSearchKeyDown
    } from "$lib/form/Choice";
    import {ChoiceHandler, ChoicesFilter} from "$lib/form/Choice";
    import {Button, Dropdown, Search} from "flowbite-svelte";
    import {createEventDispatcher} from "svelte";
    import CheckboxItems from "$lib/form/CheckboxItems.svelte";

    // eslint-disable-next-line @typescript-eslint/no-unused-vars
    interface $$Slots {
        default: { choice: Choice },
        button: { selectedChoices: Choice[] };
    }

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

    async function internalOnCreate(event: Event) {
        if (!onCreate) {
            throw new Error('No onCreate callback provided.');
        }

        event.stopPropagation();

        const newChoice = await onCreate(searchQuery);

        // Use assignment (instead of push) to make sure reactive statements are triggered
        // choices = [...choices, newChoice];
        checkboxGroup = [...checkboxGroup, getValue(newChoice)];
        searchQuery = '';

        dispatchChangeEvent(getSelectedChoices(checkboxGroup));
    }

    function onChange(event: Event & { currentTarget: EventTarget & HTMLInputElement }) {
        const value = event.currentTarget.value;
        const index = checkboxGroup.findIndex(groupItemValue => groupItemValue === value);
        const checked = event.currentTarget.checked;

        if (checked && index === -1) {
            checkboxGroup = [...checkboxGroup, value];
        } else if (!checked && index !== -1) {
            checkboxGroup.splice(index, 1);
            checkboxGroup = checkboxGroup;
        }

        event.stopPropagation();
        dispatchChangeEvent(getSelectedChoices(checkboxGroup));
    }

    function dispatchChangeEvent(selectedChoices: Choice[]) {
        dispatch('change', {
            values: selectedChoices.map(choice => getValue(choice)),
            choices: selectedChoices,
        });

        console.debug('Change event dispatched');
    }

    function getSelectedChoices(checkboxGroup: ValueType): Choice[] {
        return checkboxGroup.map(value => (choiceHandler.choicesMap.get(value.toString()) as Choice))
            // Filter out undefined. This can happen in case of data corruption, but we don't want to fail the application on that.
            .filter(value => value !== undefined);
    }

    let searchQuery = '';
    let open: boolean;
    export let checkboxGroup: ValueType = value;

    $: filterHandler = new ChoicesFilter<Choice>(filter, getFilterValue);
    $: choiceHandler = new ChoiceHandler<Choice>(choices, getValue);
    $: filteredChoices = filterHandler.getFilteredChoices(choices, searchQuery);
    $: selectedChoices = getSelectedChoices(checkboxGroup);
</script>

{#if clickToShow}
    <Button class="button whitespace-nowrap" color="alternative" size="xs">
        <slot name="button" selectedChoices="{selectedChoices}"></slot>
    </Button>
    <Dropdown bind:open class="{dropdownClass} overflow-y-auto px-3 pb-3 text-sm h-44" tabindex={-2}>
        <!-- Not possible yet, see: https://github.com/sveltejs/svelte/issues/5604 -->
        <!--{#if filterHandler.hasFilter()}-->
        <div class="p-3" slot="header" on:keydown={onSearchKeyDown}>
            <Search autofocus bind:value={searchQuery} size="md"/>
        </div>
        <!--{/if}-->
        <CheckboxItems searchQuery={searchQuery} filteredChoices={filteredChoices} internalOnCreate={internalOnCreate}
                       getValue={getValue} checkboxGroup={checkboxGroup} onChange={onChange} let:choice>
            <slot choice={choice}/>
        </CheckboxItems>
    </Dropdown>
{:else}
    <div class="p-3" on:keydown={onSearchKeyDown}>
        <Search autofocus bind:value={searchQuery} size="md"/>
    </div>
    <ul>
        <CheckboxItems searchQuery={searchQuery} filteredChoices={filteredChoices} internalOnCreate={internalOnCreate}
                       getValue={getValue} checkboxGroup={checkboxGroup} onChange={onChange} let:choice>
            <slot choice={choice}/>
        </CheckboxItems>
    </ul>
{/if}

<input name="{`${name}{}`}" type="hidden" value="{JSON.stringify(checkboxGroup)}"/>
