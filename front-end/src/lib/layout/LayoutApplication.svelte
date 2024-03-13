<script lang="ts">
    import "$src/app.scss";
    import {formModal} from "$src/stores";
    import {toastStore} from "$src/stores/ToastStore";
    import {dataStore} from "$src/stores/DataStore";
    import TasksIcon from "$lib/svg/TasksIcon.svelte";
    import ProjectIcon from "$lib/svg/ProjectIcon.svelte";
    import {Button, Modal, Spinner} from 'flowbite-svelte';
    import CreateTaskModal from "$lib/tasks/CreateTaskModal.svelte";
    import {navigating} from "$app/stores";
    import Toast from "$lib/Toast.svelte";
    import ContextMenu from "$lib/ContextMenu.svelte";
    import {dataStoreApiClient} from "$src/api/DataStoreApiClient";
    import DiscordIcon from "$lib/svg/DiscordIcon.svelte";
    import {Mail} from 'lucide-svelte';
</script>

<!-- Sidebar -->
<div
    class="hs-overlay hs-overlay-open:translate-x-0 transition-all duration-300
    fixed top-0 left-0 bottom-0 z-[40] w-64 bg-white border-r border-gray-200 overflow-y-auto
    scrollbar-y
    flex"
    id="application-sidebar">
    <nav class="hs-accordion-group p-6 w-full flex flex-col flex-wrap grow" data-hs-accordion-always-open>
        <ul class="space-y-1.5 max-w-[100%] grow">
            <!--            <li class="pb-1.5">-->
            <li>
                <div class="flex justify-between">
                    <div class="flex">
                        {#if $navigating}
                            <div class="w-7">
                                <Spinner color="blue" size={4}/>
                            </div>
                        {/if}
                        <!--                        <span class="font-bold">-->
                        <!--                            <a href="/">Fluxa</a>-->
                        <!--                        </span>-->
                    </div>
                    <!--                    <img alt="Image Description"-->
                    <!--                         class="inline-block h-6 w-6 rounded-full ring-2 ring-white dark:ring-gray-800"-->
                    <!--                         src="https://images.unsplash.com/photo-1568602471122-7832951cc4c5?ixlib=rb-4.0.3&amp;ixid=MnwxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8&amp;auto=format&amp;fit=facearea&amp;facepad=2&amp;w=320&amp;h=320&amp;q=80">-->
                </div>
            </li>
            <li class="pb-4">
                <Button class="text-sm w-full font-bold" on:click={() => ($formModal = true)} size="xs">
                    Add task
                </Button>
            </li>
            <!--            <li>-->
            <!--                <a class="flex items-center gap-x-3.5 py-2 pr-2.5 text-sm text-slate-700 rounded-md hover:bg-gray-100 dark:bg-gray-800 dark:text-slate-400 dark:hover:text-slate-300"-->
            <!--                   href="/">-->
            <!--                    <svg aria-hidden="true"-->
            <!--                         class="icon_149cb72cec icon_149cb72cec leftIcon_8403aaa9cc noFocusStyle_9a0459292c"-->
            <!--                         data-testid="icon" fill="currentColor" height="18"-->
            <!--                         viewBox="0 0 20 20"-->
            <!--                         width="18">-->
            <!--                        <path clip-rule="evenodd"-->
            <!--                              d="M5.99986 1.82129C6.41407 1.82129 6.74986 2.15708 6.74986 2.57129V4.10701H13.2499V2.57129C13.2499 2.15708 13.5856 1.82129 13.9999 1.82129C14.4141 1.82129 14.7499 2.15708 14.7499 2.57129V4.107H16.2856C16.7876 4.107 17.269 4.30643 17.624 4.66141C17.979 5.01639 18.1784 5.49784 18.1784 5.99986V16.2856C18.1784 16.7876 17.979 17.269 17.624 17.624C17.269 17.979 16.7876 18.1784 16.2856 18.1784H3.71415C3.21213 18.1784 2.73067 17.979 2.37569 17.624C2.02071 17.269 1.82129 16.7876 1.82129 16.2856V5.99986C1.82129 5.49784 2.02071 5.01639 2.37569 4.66141C2.73067 4.30643 3.21213 4.107 3.71415 4.107C3.763 4.107 3.81077 4.11168 3.85702 4.1206C3.90326 4.11168 3.95102 4.10701 3.99986 4.10701H5.24986V2.57129C5.24986 2.15708 5.58565 1.82129 5.99986 1.82129ZM5.24986 7.14272V5.60701H3.99986C3.95101 5.60701 3.90324 5.60234 3.85699 5.59342C3.81075 5.60233 3.76299 5.607 3.71415 5.607C3.60995 5.607 3.51003 5.64839 3.43635 5.72207C3.36268 5.79574 3.32129 5.89567 3.32129 5.99986V16.2856C3.32129 16.3898 3.36268 16.4897 3.43635 16.5634C3.51003 16.637 3.60995 16.6784 3.71415 16.6784H16.2856C16.3898 16.6784 16.4897 16.637 16.5634 16.5634C16.637 16.4897 16.6784 16.3898 16.6784 16.2856V5.99986C16.6784 5.89567 16.637 5.79574 16.5634 5.72207C16.4897 5.64839 16.3898 5.607 16.2856 5.607H14.7499V7.14272C14.7499 7.55693 14.4141 7.89272 13.9999 7.89272C13.5856 7.89272 13.2499 7.55693 13.2499 7.14272V5.60701H6.74986V7.14272C6.74986 7.55693 6.41407 7.89272 5.99986 7.89272C5.58565 7.89272 5.24986 7.55693 5.24986 7.14272ZM13.4214 9.92231C13.6942 9.61058 13.6626 9.13676 13.3509 8.864C13.0392 8.59124 12.5653 8.62283 12.2926 8.93455L8.75058 12.9825L7.02129 11.6856C6.68992 11.437 6.21982 11.5042 5.97129 11.8356C5.72276 12.1669 5.78992 12.637 6.12129 12.8856L8.407 14.5999C8.72086 14.8353 9.16309 14.789 9.42144 14.4937L13.4214 9.92231Z"-->
            <!--                              fill="currentColor" fill-rule="evenodd"></path>-->
            <!--                    </svg>-->
            <!--                    My tasks-->
            <!--                </a>-->
            <!--            </li>-->
            <!--            <li>-->
            <!--                <a class="flex items-center gap-x-3.5 py-2 pr-2.5 text-sm text-slate-700 rounded-md hover:bg-gray-100 dark:bg-gray-800 dark:text-slate-400 dark:hover:text-slate-300"-->
            <!--                   href="/">-->
            <!--                    <Pencil/>-->
            <!--                    My notes-->
            <!--                </a>-->
            <!--            </li>-->
            <!--            <li class="pb-1.5">-->
            <!--                <a class="flex items-center gap-x-3.5 py-2 pr-2.5 text-sm text-slate-700 rounded-md hover:bg-gray-100 dark:bg-gray-800 dark:text-slate-400 dark:hover:text-slate-300"-->
            <!--                   href="/projects">-->
            <!--                    <ProjectIcon/>-->
            <!--                    Projects-->
            <!--                </a>-->
            <!--            </li>-->
            <li>
                <div class="text-gray-600 font-bold text-sm">Your teams</div>
                {#if $dataStore.teams}
                    <ul class="hs-accordion-group pt-2" data-hs-accordion-always-open="">
                        {#each $dataStore.teams as team, i}
                            <li class="hs-accordion border-b border-gray-200 pb-1 mb-1"
                                id="users-accordion-sub-{i}">
                                <a class="hs-accordion-toggle flex items-center gap-x-3.5 py-2 pr-2.5 hs-accordion-active:text-blue-600 hs-accordion-active:hover:bg-transparent text-sm text-slate-700 rounded-md hover:bg-gray-100 dark:bg-gray-800 dark:hover:bg-gray-900 dark:text-slate-400 dark:hover:text-slate-300 dark:hs-accordion-active:text-white"
                                   href="javascript:">

                                    <span class="w-3 h-3 rounded-full bg-orange-400"
                                          style="background-color: {team.properties.color}"></span>
                                    {team.name}

                                    <svg
                                        class="hs-accordion-active:block ml-auto hidden w-3 h-3 text-gray-600 group-hover:text-gray-500 dark:text-gray-400"
                                        width="16" height="16" viewBox="0 0 16 16" fill="none"
                                        xmlns="http://www.w3.org/2000/svg">
                                        <path
                                            d="M2 11L8.16086 5.31305C8.35239 5.13625 8.64761 5.13625 8.83914 5.31305L15 11"
                                            stroke="currentColor" stroke-width="2" stroke-linecap="round"></path>
                                    </svg>

                                    <svg
                                        class="hs-accordion-active:hidden ml-auto block w-3 h-3 text-gray-600 group-hover:text-gray-500 dark:text-gray-400"
                                        width="16" height="16" viewBox="0 0 16 16" fill="none"
                                        xmlns="http://www.w3.org/2000/svg">
                                        <path
                                            d="M2 5L8.16086 10.6869C8.35239 10.8637 8.64761 10.8637 8.83914 10.6869L15 5"
                                            stroke="currentColor" stroke-width="2" stroke-linecap="round"></path>
                                    </svg>
                                </a>

                                <div id="users-accordion-sub-{i}-child"
                                     class="hs-accordion-content w-full overflow-hidden transition-[height] duration-300 hidden text-sm text-slate-700 rounded-md dark:bg-gray-800 dark:text-slate-400">
                                    <ul class="">
                                        <li>
                                            <a class="flex items-center gap-x-3.5 py-2 pr-2.5 text-sm text-slate-700 rounded-md hover:bg-gray-100 dark:bg-gray-800 dark:text-slate-400 dark:hover:text-slate-300"
                                               href="/teams/{team.slug}/tasks">
                                                <TasksIcon/>
                                                Tasks
                                            </a>
                                        </li>
                                        <!--                                        <li>-->
                                        <!--                                            <a class="flex items-center gap-x-3.5 py-2 pr-2.5 text-sm text-slate-700 rounded-md hover:bg-gray-100 dark:bg-gray-800 dark:text-slate-400 dark:hover:text-slate-300"-->
                                        <!--                                               href="/teams/{team.slug}/cycles">-->
                                        <!--                                                <CycleIcon/>-->
                                        <!--                                                Cycles-->
                                        <!--                                            </a>-->
                                        <!--                                        </li>-->
                                        <li>
                                            <div class="hs-accordion-group">
                                                <div class="hs-accordion"
                                                     id="hs-basic-with-title-and-arrow-stretched-heading-{i}">
                                                    <button
                                                        class="hs-accordion-toggle hs-accordion-active:text-blue-600 group inline-flex items-center justify-between gap-x-3 w-full text-left text-gray-800 transition hover:text-gray-500 dark:hs-accordion-active:text-blue-500 dark:text-gray-200 dark:hover:text-gray-400"
                                                        aria-controls="hs-basic-with-title-and-arrow-stretched-collapse-one">
                                                        <a href="/"
                                                           class="flex items-center gap-x-3.5 py-2 pr-2.5 text-sm text-slate-700 rounded-md dark:bg-gray-800 dark:text-slate-400 dark:hover:text-slate-300">
                                                            <ProjectIcon/>
                                                            Projects
                                                        </a>
                                                        <svg
                                                            class="hs-accordion-active:hidden hs-accordion-active:text-blue-600 hs-accordion-active:group-hover:text-blue-600 block w-3 h-3 text-gray-600 group-hover:text-gray-500 dark:text-gray-400"
                                                            width="16" height="16" viewBox="0 0 16 16" fill="none"
                                                            xmlns="http://www.w3.org/2000/svg">
                                                            <path
                                                                d="M2 5L8.16086 10.6869C8.35239 10.8637 8.64761 10.8637 8.83914 10.6869L15 5"
                                                                stroke="currentColor" stroke-width="2"
                                                                stroke-linecap="round"/>
                                                        </svg>
                                                        <svg
                                                            class="hs-accordion-active:block hs-accordion-active:text-blue-600 hs-accordion-active:group-hover:text-blue-600 hidden w-3 h-3 text-gray-600 group-hover:text-gray-500 dark:text-gray-400"
                                                            width="16" height="16" viewBox="0 0 16 16" fill="none"
                                                            xmlns="http://www.w3.org/2000/svg">
                                                            <path
                                                                d="M2 11L8.16086 5.31305C8.35239 5.13625 8.64761 5.13625 8.83914 5.31305L15 11"
                                                                stroke="currentColor" stroke-width="2"
                                                                stroke-linecap="round"/>
                                                        </svg>
                                                    </button>
                                                    <div id="hs-basic-with-title-and-arrow-stretched-collapse-{i}"
                                                         class="hs-accordion-content hidden w-full overflow-hidden transition-[height] duration-300"
                                                         aria-labelledby="hs-basic-with-title-and-arrow-stretched-heading-one">
                                                        <ul class="pl-8 whitespace-nowrap">
                                                            {#each team.projectIds as projectId}
                                                                {@const project = $dataStoreApiClient.getProject(projectId)}

                                                                <li class="overflow-ellipsis overflow-hidden">
                                                                    <a class="flex items-center gap-x-3.5 py-2 pr-2.5 text-sm text-slate-700 rounded-md hover:bg-gray-100 dark:bg-gray-800 dark:text-slate-400 dark:hover:text-slate-300"
                                                                       href="/projects/{project.code}">{project.name}</a>
                                                                </li>
                                                            {/each}
                                                        </ul>
                                                    </div>
                                                </div>
                                            </div>
                                        </li>
                                    </ul>
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
               href="https://discord.gg/SdnparkQ" target="_blank">
                <DiscordIcon class="mr-1"/>
                Join on Discord
            </a>
        </div>
    </nav>
</div>
<!-- End Sidebar -->

<!-- Content -->
<div class="w-full pl-64 flex flex-col grow">
    <slot/>
</div>

<ContextMenu></ContextMenu>

<Modal autoclose={false} bind:open={$formModal} outsideclose>
    <CreateTaskModal/>
</Modal>

<div class="fixed bottom-0 right-0 mr-1">
    {#each $toastStore as {id, type, message}}
        <Toast id={id} type={type}>
            {@html message}
        </Toast>
    {/each}
</div>
