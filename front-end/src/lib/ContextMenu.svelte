<script lang="ts">
    import {ChevronRight} from 'lucide-svelte';
    import {contextMenuData, contextMenuEvent} from "$src/stores/ContextMenuStore";
    import type {Component, Cycle, Group, Label, Milestone, Project, Task, Team} from "$src/api/schema/schema";
    import StatusChoice from "$lib/form/Status.svelte";
    import MilestoneChoice from "$lib/form/Milestone.svelte";
    import LabelsChoice from "$lib/form/Labels.svelte";
    import GroupsChoice from "$lib/form/Groups.svelte";
    import ComponentChoice from "$lib/form/Components.svelte";
    import {api} from "$src/api/ServerApiClient";
    import type {ReferenceElement} from "@floating-ui/dom";
    import {computePosition, flip} from "@floating-ui/dom";
    import {browser} from '$app/environment';
    import {onMount} from 'svelte';
    import {dataStoreApiClient} from "$src/api/DataStoreApiClient";

    // TODO: Check what users expect here. I tried both but I may prefer the one in which the user will navigate directly when a row is clicked.
    // This because we clearly indicate the row will cause navigation by using the pointer cursor.
    // import {beforeNavigate} from '$app/navigation';
    // import type {BeforeNavigate} from "@sveltejs/kit/src/exports/public";
    // beforeNavigate((navigate: BeforeNavigate) => {
    //     if (isOpen) {
    //         navigate.cancel();
    //     }
    // });

    function close() {
        isOpen = false;
        contextMenu.style.display = 'none';
        closeSubmenus();
    }

    function handleClickOutside(event: PointerEvent) {
        if (isOpen && contextMenu && !contextMenu.contains(event.target)) {
            event.stopPropagation();
            event.preventDefault();

            close();
        }
    }

    function handleEscapeKeyPress(event) {
        if (event.key === 'Escape') {
            close();
        }
    }

    onMount(() => {
        document.addEventListener('click', handleClickOutside);
        document.addEventListener('keydown', handleEscapeKeyPress);

        return () => {
            document.removeEventListener('click', handleClickOutside);
            document.removeEventListener('keydown', handleEscapeKeyPress);
        }
    });

    // afterUpdate(() => {
    //     contextMenu.querySelectorAll('[data-sub-menu-trigger]').forEach(subMenu => {
    //         subMenu.addEventListener('mouseenter', event => {
    //             console.log('mouseenter', event);
    //         });
    //     });
    // });

    function openSubmenu(event: MouseEvent & {
        currentTarget: EventTarget & HTMLElement
    }) {
        closeSubmenus();

        const subMenuTrigger = event.currentTarget;
        const subMenu = subMenuTrigger.nextElementSibling as HTMLElement;
        subMenu.style.display = '';

        computePosition(subMenuTrigger, subMenu, {
            placement: 'right-start',
            middleware: [
                flip(),
            ],
        }).then(({x, y}) => {
            console.debug('x,y', x, y);

            Object.assign(subMenu.style, {
                left: `${x}px`,
                top: `${y}px`,
            });
        });
    }

    function closeSubmenus() {
        contextMenu.querySelectorAll('.sub-menu').forEach(subMenu => subMenu.style.display = 'none');
    }

    function createVirtualElement(event: PointerEvent): ReferenceElement {
        return {
            getBoundingClientRect: () => ({
                x: event.clientX,
                y: event.clientY,
                width: 0,
                height: 0,
                left: event.clientX,
                top: event.clientY,
                right: event.clientX,
                bottom: event.clientY,
            }),
        };
    }

    // Menu values
    let task: Task;
    let project: Project;
    let team: Team;
    let projects: Project[] = [];
    let milestones: Milestone[] = [];
    let components: Component[] = [];
    let projectGroups: Group[] = [];
    let cycles: Cycle[] = [];
    let labels: Label[] = [];

    $: if ($contextMenuData) {
        console.debug('Loading context menu items');

        task = $dataStoreApiClient.getTask($contextMenuData.taskId);
        project = $dataStoreApiClient.getProject(task.projectId);
        team = $dataStoreApiClient.getTeam(task.teamId);
        projects = $dataStoreApiClient.getProjectsByTeamId(team.id);
        milestones = project.milestoneIds.map(id => $dataStoreApiClient.getMilestone(id));
        components = project.componentIds.map(id => $dataStoreApiClient.getComponent(id));
        projectGroups = project.groupIds.map(id => $dataStoreApiClient.getGroup(id));
        cycles = team.cycleIds.map(id => $dataStoreApiClient.getCycle(id));
        labels = team.labelIds!.map(id => $dataStoreApiClient.getLabel(id));
    }

    // This part should be done in an action I think, now it will be executed over and over while
    // it should only execute once per event

    let isOpen = false;
    let contextMenu: HTMLElement;
    $: if (browser && $contextMenuEvent && contextMenu) {
        open($contextMenuEvent);
        $contextMenuEvent = undefined;
    }

    function open(event: PointerEvent) {
        isOpen = true;

        const virtualElement = createVirtualElement(event);
        console.log('virtualElement', virtualElement, contextMenu);

        contextMenu.style.display = '';
        computePosition(virtualElement, contextMenu, {
            placement: 'right-start',
            middleware: [
                flip(),
            ],
        }).then(({x, y}) => {
            console.debug('x,y', x, y);

            contextMenu.style.left = `${x}px`;
            contextMenu.style.top = `${y}px`;
        });
    }
</script>

<style lang="scss">
  .background {
    position: fixed;
    inset: 0;
    z-index: 100;
  }

  .menu {
    //max-height: 300px;
    min-width: 220px;
    border: 0.5px solid rgb(216, 216, 216);
    box-shadow: rgba(0, 0, 0, 0.09) 0px 3px 12px;
    background: white;
    display: flex;
    flex-direction: column;

    .item {
      position: relative;
      cursor: default;
      padding: .5rem 1rem;
      //@apply relative h-6 min-h-[24px] select-none rounded-md pl-6 pr-1;
      //@apply z-20 text-magnum-900 outline-none;
      //@apply data-[highlighted]:bg-magnum-200 data-[highlighted]:text-magnum-900;
      //@apply data-[disabled]:text-neutral-300;
      display: flex;
      justify-content: space-between;
      //@apply flex items-center text-sm leading-none;
      //@apply ring-0 !important;
      &:hover {
        background-color: lightgrey;
      }
    }
  }

  .context-menu, .sub-menu {
    /* Float on top of the UI */
    position: absolute;

    /* Avoid layout interference */
    width: max-content;
    top: 0;
    left: 0;

    z-index: 150;
  }
</style>

{#if isOpen}
{/if}

<div bind:this={contextMenu} class="context-menu menu shadow rounded text-sm text-gray-600" style="display: none;">
    {#key $contextMenuData}
        {#if task}
            <div class="item" on:mouseenter={openSubmenu}>
                Status
                <div>
                    <ChevronRight class="square-4"/>
                </div>
            </div>
            <div class="menu sub-menu" style="display: none;">
                <StatusChoice
                        clickToShow="{false}"
                        choices="{team?.workflow?.statuses ?? []}"
                        on:change={(event)=>{
                          api.putTask(task.id, {statusId: event.detail.value});
                          close();
                        }}
                        value={task.statusId}
                />
            </div>
            <div class="item" on:mouseenter={openSubmenu}>
                Labels
                <div>
                    <ChevronRight class="square-4"/>
                </div>
            </div>
            <div class="menu sub-menu" style="display: none;">
                <LabelsChoice
                        choices="{labels}"
                        clickToShow="{false}"
                        value={$dataStoreApiClient.getTeamLabels(task.labelIds).map(label => label.id)}
                        on:change={(event)=>{
                          api.putTask(task.id, {labelIds: event.detail.values});
                        }}
                        baseComponent="{{teamId: team.id}}"
                />
            </div>
            <div class="item" on:mouseenter={openSubmenu}>
                Components
                <div>
                    <ChevronRight class="square-4"/>
                </div>
            </div>
            <div class="menu sub-menu" style="display: none;">
                <ComponentChoice
                        choices="{components}"
                        clickToShow="{false}"
                        value={task.componentIds}
                        on:change={(event)=>{
                          api.putTask(task.id, {componentIds: event.detail.values});
                        }}
                        baseComponent="{{projectId: project.id}}"
                />
            </div>
            <div class="item" on:mouseenter={openSubmenu}>
                Groups
                <div>
                    <ChevronRight class="square-4"/>
                </div>
            </div>
            <div class="menu sub-menu" style="display: none;">
                <GroupsChoice
                        choices="{projectGroups}"
                        clickToShow="{false}"
                        value={task.projectGroupIds}
                        on:change={(event)=>{
                          api.putTask(task.id, {projectGroupIds: event.detail.values});
                        }}
                        baseComponent="{{projectId: project.id}}"
                />
            </div>
            <div class="item" on:mouseenter={openSubmenu}>
                Milestone
                <div>
                    <ChevronRight class="square-4"/>
                </div>
            </div>
            <div class="menu sub-menu" style="display: none;">
                <MilestoneChoice
                        choices="{milestones}"
                        clickToShow="{false}"
                        value={task.milestoneId}
                        on:change={(event)=>{
                          api.putTask(task.id, {milestoneId: event.detail.value});
                          close();
                        }}
                        baseComponent="{{projectId: project.id}}"
                />
            </div>
        {/if}
    {/key}
</div>
