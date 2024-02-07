<script lang="ts">
    import {ArrowLeft} from 'lucide-svelte';
    import {createDropdownMenu, melt} from "@melt-ui/svelte";
    import {fly} from 'svelte/transition';
    import {context, GROUPS} from "$src/stores/ContextStore";

    export let breadcrumb;

    const {
        elements: {trigger, menu, item, separator, arrow},
        builders: {createSubmenu},
        states: {open},
    } = createDropdownMenu({
        forceVisible: true,
        loop: true,
    });

    const {
        elements: {subMenu, subTrigger},
        states: {subOpen},
    } = createSubmenu();

    $: tooltipContent = '';
</script>

<style lang="scss">
    header {
        position: sticky;
        top: 0;
        left: 0;
        display: flex;
        flex-wrap: nowrap;
        background-color: white;
        width: 100%;
        z-index: 10;
        align-items: center;

        .search {
            flex-grow: 1;
        }

        .breadcrumb {
            font-size: 1rem;
        }
    }

    .view-wrapper {
        border: 1px solid rgb(216, 216, 216);
    }

    .view {
        font-size: .8rem;

        .head {
            font-weight: 500;
            padding: 0.3rem 0;
        }

        label {
            display: flex;
            padding: 0.3rem 0;
            align-items: center;
        }

        input[type='radio'] {
            width: 12px;
            height: 12px;
            margin: 0 .2rem 0 0;

            &:focus {
                box-shadow: none;
            }
        }
    }
</style>

<header
    class="sm:justify-start sm:flex-nowrap border-b border-gray-200 text-sm py-3 sm:py-0 dark:bg-gray-800 dark:border-gray-700">
    <button class="p-2" on:click={()=>{history.back();}}>
        <ArrowLeft class="w-[100%] h-[100%]" size="20" strokeWidth="2"/>
    </button>
    <span class="breadcrumb flex-none dark:text-white pr-4">{breadcrumb}</span>
    <div
        class="search flex mr-1 flex-col gap-y-4 gap-x-4 mt-5 sm:flex-row sm:items-center sm:justify-end sm:gap-y-0 sm:mt-0 sm:pl-7">
        <!--        <div class="my-2">-->
        <!--            <label class="sr-only" for="icon">Search</label>-->
        <!--            <div class="relative">-->
        <!--                <div class="absolute inset-y-0 left-0 flex items-center pointer-events-none z-20 pl-4">-->
        <!--                    <svg class="h-4 w-4 text-gray-400" fill="currentColor" height="16" viewBox="0 0 16 16"-->
        <!--                         width="16" xmlns="http://www.w3.org/2000/svg">-->
        <!--                        <path-->
        <!--                            d="M11.742 10.344a6.5 6.5 0 1 0-1.397 1.398h-.001c.03.04.062.078.098.115l3.85 3.85a1 1 0 0 0 1.415-1.414l-3.85-3.85a1.007 1.007 0 0 0-.115-.1zM12 6.5a5.5 5.5 0 1 1-11 0 5.5 5.5 0 0 1 11 0z"/>-->
        <!--                    </svg>-->
        <!--                </div>-->
        <!--                <input-->
        <!--                    class="py-1 px-4 pl-11 block w-full border-gray-200 shadow-sm rounded-md text-sm focus:z-10 focus:border-blue-500 focus:ring-blue-500 dark:bg-gray-800 dark:border-gray-700 dark:text-gray-400"-->
        <!--                    id="icon" name="icon"-->
        <!--                    placeholder="Search"-->
        <!--                    type="text">-->
        <!--            </div>-->
        <!--        </div>-->

        <div id="layouts">
<!--            <Tooltip triggeredBy="#layouts button[data-tooltip]">-->
<!--                {tooltipContent}-->
<!--            </Tooltip>-->

            <div class="flex items-center gap-x-1">
<!--                <button-->
<!--                    class="grid h-7 w-7 place-items-center rounded p-1 outline-none hover:bg-custom-sidebar-background-80 duration-300 bg-custom-sidebar-background-80"-->
<!--                    data-tooltip-->
<!--                    on:mouseenter={()=>{tooltipContent='List'}}-->
<!--                    on:click={()=>$context.layout=LAYOUTS.LIST}-->
<!--                    type="button">-->
<!--                    <svg aria-hidden="true" class="MuiSvgIcon-root MuiSvgIcon-fontSizeMedium css-57kesc"-->
<!--                         data-testid="FormatListBulletedOutlinedIcon" focusable="false" viewBox="0 0 24 24">-->
<!--                        <path-->
<!--                            d="M4 10.5c-.83 0-1.5.67-1.5 1.5s.67 1.5 1.5 1.5 1.5-.67 1.5-1.5-.67-1.5-1.5-1.5zm0-6c-.83 0-1.5.67-1.5 1.5S3.17 7.5 4 7.5 5.5 6.83 5.5 6 4.83 4.5 4 4.5zm0 12c-.83 0-1.5.68-1.5 1.5s.68 1.5 1.5 1.5 1.5-.68 1.5-1.5-.67-1.5-1.5-1.5zM7 19h14v-2H7v2zm0-6h14v-2H7v2zm0-8v2h14V5H7z"></path>-->
<!--                    </svg>-->
<!--                </button>-->
<!--                <button-->
<!--                    class="grid h-7 w-7 place-items-center rounded p-1 outline-none hover:bg-custom-sidebar-background-80 duration-300 text-custom-sidebar-text-200"-->
<!--                    data-tooltip-->
<!--                    on:mouseenter={()=>{tooltipContent='Swimlanes'}}-->
<!--                    on:click={()=>$context.layout=LAYOUTS.SWIMLANES}-->
<!--                    type="button">-->
<!--                    <svg aria-hidden="true" class="MuiSvgIcon-root MuiSvgIcon-fontSizeMedium css-57kesc"-->
<!--                         data-testid="GridViewOutlinedIcon" focusable="false" viewBox="0 0 24 24">-->
<!--                        <path-->
<!--                            d="M3 3v8h8V3H3zm6 6H5V5h4v4zm-6 4v8h8v-8H3zm6 6H5v-4h4v4zm4-16v8h8V3h-8zm6 6h-4V5h4v4zm-6 4v8h8v-8h-8zm6 6h-4v-4h4v4z"></path>-->
<!--                    </svg>-->
<!--                </button>-->
<!--                <button-->
<!--                    class="grid h-7 w-7 place-items-center rounded p-1 outline-none hover:bg-custom-sidebar-background-80 duration-300 text-custom-sidebar-text-200"-->
<!--                    data-tooltip-->
<!--                    on:mouseenter={()=>{tooltipContent='Calendar'}}-->
<!--                    on:click={()=>$context.layout=LAYOUTS.CALENDAR}-->
<!--                    type="button">-->
<!--                    <svg aria-hidden="true" class="MuiSvgIcon-root MuiSvgIcon-fontSizeMedium css-57kesc"-->
<!--                         data-testid="CalendarMonthOutlinedIcon" focusable="false" viewBox="0 0 24 24">-->
<!--                        <path-->
<!--                            d="M19 4h-1V2h-2v2H8V2H6v2H5c-1.11 0-1.99.9-1.99 2L3 20c0 1.1.89 2 2 2h14c1.1 0 2-.9 2-2V6c0-1.1-.9-2-2-2zm0 16H5V10h14v10zm0-12H5V6h14v2zM9 14H7v-2h2v2zm4 0h-2v-2h2v2zm4 0h-2v-2h2v2zm-8 4H7v-2h2v2zm4 0h-2v-2h2v2zm4 0h-2v-2h2v2z"></path>-->
<!--                    </svg>-->
<!--                </button>-->
<!--                <button-->
<!--                    class="grid h-7 w-7 place-items-center rounded p-1 outline-none hover:bg-custom-sidebar-background-80 duration-300 text-custom-sidebar-text-200"-->
<!--                    data-tooltip-->
<!--                    on:mouseenter={()=>{tooltipContent='Spreadsheet'}}-->
<!--                    on:click={()=>$context.layout=LAYOUTS.SPREADSHEET}-->
<!--                    type="button">-->
<!--                    <svg aria-hidden="true" class="MuiSvgIcon-root MuiSvgIcon-fontSizeMedium css-57kesc"-->
<!--                         data-testid="TableChartOutlinedIcon" focusable="false" viewBox="0 0 24 24">-->
<!--                        <path-->
<!--                            d="M20 3H5c-1.1 0-2 .9-2 2v14c0 1.1.9 2 2 2h15c1.1 0 2-.9 2-2V5c0-1.1-.9-2-2-2zm0 2v3H5V5h15zm-5 14h-5v-9h5v9zM5 10h3v9H5v-9zm12 9v-9h3v9h-3z"></path>-->
<!--                    </svg>-->
<!--                </button>-->
<!--                <button-->
<!--                    class="grid h-7 w-7 place-items-center rounded p-1 outline-none hover:bg-custom-sidebar-background-80 duration-300 text-custom-sidebar-text-200"-->
<!--                    data-tooltip-->
<!--                    on:mouseenter={()=>{tooltipContent='Gantt chart'}}-->
<!--                    on:click={()=>$context.layout=LAYOUTS.GANTT}-->
<!--                    type="button">-->
<!--                    <svg aria-hidden="true"-->
<!--                         class="MuiSvgIcon-root MuiSvgIcon-fontSizeMedium rotate-90 css-57kesc"-->
<!--                         data-testid="WaterfallChartOutlinedIcon" focusable="false"-->
<!--                         viewBox="0 0 24 24">-->
<!--                        <path d="M18 4h3v16h-3V4zM3 13h3v7H3v-7zm11-9h3v3h-3V4zm-4 1h3v4h-3V5zm-3 5h3v4H7v-4z"></path>-->
<!--                    </svg>-->
<!--                </button>-->

                <button class="my-2 py-1 px-2 inline-flex justify-center items-center gap-2 rounded-md border
                        font-medium bg-white text-gray-700 shadow-sm align-middle hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2
                        focus:ring-offset-white focus:ring-blue-600 transition-all text-sm dark:bg-slate-900 dark:hover:bg-slate-800 dark:border-gray-700
                        dark:text-gray-400 dark:hover:text-white dark:focus:ring-offset-gray-800"
                        type="button"
                        use:melt={$trigger}>
                    View
                    <svg class="w-2.5 h-2.5 text-gray-600" fill="none" height="16"
                         viewBox="0 0 16 16" width="16" xmlns="http://www.w3.org/2000/svg">
                        <path d="M2 5L8.16086 10.6869C8.35239 10.8637 8.64761 10.8637 8.83914 10.6869L15 5"
                              stroke="currentColor" stroke-linecap="round" stroke-width="2"/>
                    </svg>
                </button>


                {#if $open}
                    <div use:melt={$menu} transition:fly={{ duration: 150, y: -10 }}
                         class="w-72 h-72 z-10 min-w-[15rem] bg-white shadow-md
                         rounded-lg py-2 px-4 dark:bg-gray-800 dark:border dark:border-gray-700
                         dark:divide-gray-700 text-sm text-gray-800 view-wrapper">
                        <div class="view">
                            <div class="head">Group by</div>
                            <label>
                                <input type="radio" bind:group={$context.groupBy} value="{GROUPS.STATUS}">
                                Status
                            </label>
                            <label>
                                <input type="radio" bind:group={$context.groupBy} value="{GROUPS.LABEL}">
                                Label
                            </label>
                            <label>
                                <input type="radio" bind:group={$context.groupBy} value="{GROUPS.PROJECT}">
                                Project
                            </label>
                            <label>
                                <input type="radio" bind:group={$context.groupBy} value="{GROUPS.GROUP}">
                                Group
                            </label>
                            <label>
                                <input type="radio" bind:group={$context.groupBy} value="{GROUPS.MILESTONE}">
                                Milestone
                            </label>
                            <label>
                                <input type="radio" bind:group={$context.groupBy} value="{GROUPS.COMPONENT}">
                                Component
                            </label>
                            <label>
                                <input type="radio" bind:group={$context.groupBy} value="{GROUPS.CYCLE}">
                                Cycle
                            </label>
                            <label>
                                <input type="radio" bind:group={$context.groupBy} value="{GROUPS.ASSIGNEE}">
                                Assignee
                            </label>
                            <label>
                                <input type="radio" bind:group={$context.groupBy} value="{GROUPS.NO_GROUPING}">
                                No grouping
                            </label>


                            <!--                            <Radio-->
                            <!--                                    choices={Object.keys(GROUPS)}-->
                            <!--                                    clickToShow={false}-->
                            <!--                                    getValue={(value)=>value}-->
                            <!--                                    let:choice={choice}-->
                            <!--                                    enableSearch={false}>-->
                            <!--                                {choice}-->
                            <!--                            </Radio>-->

                        </div>
                        <!--                        <hr/>-->
                        <!--                        <div class="flex justify-between">-->
                        <!--                            <div class="flex items-center">Order by</div>-->
                        <!--                            <select class="text-sm text-gray-800">-->
                        <!--                                {#each Object.keys(GROUPS) as key}-->
                        <!--                                    <option value="{key}">{GROUPS[key].toLowerCase()}</option>-->
                        <!--                                {/each}-->
                        <!--                            </select>-->
                        <!--                        </div>-->
                    </div>
                {/if}
            </div>
        </div>
    </div>
</header>
