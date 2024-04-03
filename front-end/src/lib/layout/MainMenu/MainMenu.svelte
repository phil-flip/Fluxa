<script lang="ts">
    import {Button, Spinner} from "flowbite-svelte";
    import {Mail} from "lucide-svelte";
    import "$src/app.scss";
    import {formModal} from "$src/stores";
    import {dataStore} from "$src/stores/DataStore";
    import {navigating} from "$app/stores";
    import DiscordIcon from "$lib/svg/DiscordIcon.svelte";
    import {createAccordion, melt} from '@melt-ui/svelte';
    import TeamMenuItems from "./TeamMenuItems.svelte";
    import StateIndicator from "$lib/layout/MainMenu/StateIndicator.svelte";

    const {
        elements: {content, item, trigger, root},
        helpers: {isSelected},
    } = createAccordion({
        multiple: true,
    });
</script>

<div
    class="transition-all duration-300
    fixed top-0 left-0 bottom-0 z-[40] w-64 bg-white border-r border-gray-200 overflow-y-auto
    scrollbar-y
    flex"
    id="application-sidebar">

    <nav class="p-6 w-full flex flex-col flex-wrap grow">
        <ul class="space-y-1.5 max-w-[100%] grow">
            <li>
                <div class="flex justify-between">
                    <div class="flex">
                        {#if $navigating}
                            <div class="w-7">
                                <Spinner color="blue" size={4}/>
                            </div>
                        {/if}
                    </div>
                </div>
            </li>
            <li class="pb-4">
                <Button class="text-sm w-full font-bold" on:click={() => ($formModal = true)} size="xs">
                    Add task
                </Button>
            </li>
            <li>
                <div class="text-gray-600 font-bold text-sm">Your spaces</div>
                {#if $dataStore.teams}
                    <ul class="pt-2" {...$root}>
                        {#each $dataStore.teams as team, i}
                            {@const spaceAccordionId = team.id }

                            <li class="border-b border-gray-200 pb-1 mb-1" use:melt={$item(spaceAccordionId)}>
                                <button class="flex w-full items-center gap-x-3.5 py-2 pr-2.5 text-sm text-slate-700 rounded-md
                                hover:bg-gray-100 dark:bg-gray-800 dark:hover:bg-gray-900 dark:text-slate-400
                                dark:hover:text-slate-300"
                                        use:melt={$trigger(spaceAccordionId)}>
                                    <span class="w-3 h-3 rounded-full bg-orange-400"
                                          style="background-color: {team.properties.color}"></span>
                                    {team.name}

                                    <StateIndicator state="{$isSelected(spaceAccordionId)}"/>
                                </button>

                                <div use:melt={$content(spaceAccordionId)} class="slide">
                                    <TeamMenuItems team={team}/>
                                </div>
                            </li>
                        {/each}
                    </ul>
                {/if}
            </li>
        </ul>

        <div class="text-red-500 text-sm text-center">
            <span class="block mb-1">This is a pre-alpha version. Expect bugs!</span>
            <a class="border rounded py-2 px-3 mb-2 border-primary-700 text-primary-700 text-sm w-full flex items-center justify-center"
               href="mailto:support@fluxa.app" target="_blank">
                <Mail class="mr-1"/>
                Report
            </a>
            <a class="border rounded py-2 px-3 border-primary-700 text-primary-700 text-sm w-full block"
               href="https://discord.gg/aqFT5H4c6u" target="_blank">
                <DiscordIcon class="mr-1"/>
                Join on Discord
            </a>
        </div>
    </nav>
</div>
