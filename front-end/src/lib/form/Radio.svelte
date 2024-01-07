<script generics="Choice" lang="ts">
    import {Button, Dropdown, Radio, Search} from "flowbite-svelte";
    import type {FilterType, GetFilterValue, GetValueType, OnCreate} from "$lib/form/Choice";
    import {ChoiceHandler, ChoicesFilter} from "$lib/form/Choice";
    import {Plus} from "lucide-svelte";
    import {createEventDispatcher} from "svelte";

    // eslint-disable-next-line @typescript-eslint/no-unused-vars
    interface $$Slots {
        default: { choice: Choice },
        button: { selectedChoice: Choice | undefined };
    }

    type ValueType = string | number;

    export let clickToShow: boolean = true;
    export let enableSearch: boolean = true;
    export let value: ValueType = '';
    export let name: string | undefined = undefined;
    export let choices: Choice[];
    export let getValue: GetValueType<Choice>;
    export let filter: FilterType<Choice> | null = null;
    export let getFilterValue: GetFilterValue<Choice> | null = null;
    export let onCreate: OnCreate<Choice> | undefined = undefined;

    const dispatch = createEventDispatcher();

    let pendingChangeEventForCreatedChoice = false;

    async function internalOnCreate(event: Event) {
        closeDropDown();

        if (!onCreate) {
            throw new Error('No onCreate callback provided.');
        }

        event.stopPropagation();

        const newChoice = await onCreate(searchQuery);

        pendingChangeEventForCreatedChoice = true;

        choices = [...choices, newChoice];
        value = getValue(newChoice);

        searchQuery = '';
    }

    function onChange(event: Event) {
        closeDropDown();

        event.stopPropagation();
        dispatchChangeEvent();
    }

    function dispatchChangeEvent() {
        dispatch('change', {
            value: value,
            choice: selectedChoice,
        });

        console.debug('Change event dispatched');
    }

    function onChangeSelectedChoice(selectedChoice: Choice | undefined) {
        console.debug('onChangeSelectedChoice called with:');
        console.debug(selectedChoice);

        if (pendingChangeEventForCreatedChoice) {
            console.debug('pendingChangeEventForCreatedChoice');

            pendingChangeEventForCreatedChoice = false;
            dispatchChangeEvent();
        }
    }

    const filterHandler = new ChoicesFilter<Choice>(filter, getFilterValue);

    function closeDropDown() {
        open = false;
    }

    // TODO: This is now disabled, if it is not needed, remove it
    // function validateValue() {
    //     if (value && !choiceHandler.choicesMap.has(value)) {
    //         console.debug('Value not found in choicesMap, value has been reset to an empty string.');
    //
    //         value = '';
    //     }
    // }

    let searchQuery = '';
    let open: boolean;

    $: choiceHandler = new ChoiceHandler<Choice>(choices, getValue);
    $: filteredChoices = enableSearch ? filterHandler.getFilteredChoices(choices, searchQuery) : choices;
    $: selectedChoice = choiceHandler.choicesMap.get(value as string);
    // $: validateValue(choices, value);

    $: onChangeSelectedChoice(selectedChoice);
</script>

<style lang="scss">
    li.choice:focus-within {
        outline: 2px solid;
    }
</style>

{#if clickToShow}
    <Button class="whitespace-nowrap" color="alternative" size="xs">
        <slot name="button" selectedChoice="{selectedChoice}"></slot>
    </Button>
    <Dropdown bind:open class="overflow-y-auto px-3 pb-3 text-sm h-44" tabindex={-2}>
        <!--        Not supported right now, fix after update to Svelte 5 with snippets:-->
        <!--        See: https://svelte-5-preview.vercel.app/docs/snippets#snippets-and-slots -->
        <!--{#if enableSearch}-->
        <div class="p-3" slot="header">
            <Search autofocus bind:value={searchQuery} size="md"/>
        </div>
        <!--{/if}-->
        {#if onCreate && searchQuery && filteredChoices.length === 0}
            <li class="choice rounded hover:bg-gray-100 dark:hover:bg-gray-600 outline-primary-500">
                <Radio on:click={internalOnCreate} class="p-2" custom>
                    <Plus strokeWidth="3" style="width: 20px;height: 20px;margin-right: .3rem"/>
                    {searchQuery}
                </Radio>
            </li>
        {/if}
        {#each filteredChoices as choice}
            <li class="choice rounded hover:bg-gray-100 dark:hover:bg-gray-600 outline-primary-500">
                <Radio on:change={onChange} bind:group={value} value="{getValue(choice)}" {...$$restProps} class="p-2">
                    <slot choice={choice}/>
                </Radio>
            </li>
        {/each}
    </Dropdown>
{:else}
    {#if enableSearch}
        <div class="p-3">
            <Search autofocus bind:value={searchQuery} size="md"/>
        </div>
    {/if}
    <ul>
        {#if onCreate && searchQuery && filteredChoices.length === 0}
            <li class="choice rounded hover:bg-gray-100 dark:hover:bg-gray-600 outline-primary-500">
                <Radio on:click={internalOnCreate} class="p-2" custom>
                    <Plus strokeWidth="3" style="width: 20px;height: 20px;margin-right: .3rem"/>
                    {searchQuery}
                </Radio>
            </li>
        {/if}
        {#each filteredChoices as choice}
            <li class="choice rounded hover:bg-gray-100 dark:hover:bg-gray-600 outline-primary-500">
                <Radio on:change={onChange}
                       bind:group={value}
                       value="{getValue(choice)}"
                       {...$$restProps}
                       class="p-2">
                    <slot choice={choice}/>
                </Radio>
            </li>
        {/each}
    </ul>
{/if}

{#if name && value !== undefined }
    <input type="hidden" name="{name}" value="{value}"/>
{/if}
