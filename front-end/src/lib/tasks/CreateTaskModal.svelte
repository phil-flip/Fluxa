<script lang="ts">
    import {Button, Input, Label as HtmlLabel, Textarea, Toggle} from "flowbite-svelte";
    import {onMount, setContext} from "svelte";
    import ProjectChoice from "$lib/form/Project.svelte";
    import TeamChoice from "$lib/form/Team.svelte";
    import {formModal} from "$src/stores";
    import type {Component, Cycle, Group, Label, Milestone, Project, Status} from "$src/api/schema/schema";
    import LabelsChoice from "$lib/form/Labels.svelte";
    import {createToast} from "$src/stores/ToastStore";
    import {context} from "$src/stores/ContextStore";
    import {dataStore} from "$src/stores/DataStore";
    import StatusChoice from "$lib/form/Status.svelte";
    import ComponentsChoice from "$lib/form/Components.svelte";
    import GroupsChoice from "$lib/form/Groups.svelte";
    import CycleChoice from "$lib/form/Cycle.svelte";
    import MilestoneChoice from "$lib/form/Milestone.svelte";
    import {dataStoreApiClient} from "$src/api/DataStoreApiClient";
    import {api} from "$src/api/ServerApiClient";
    import {dispatchDataChangeEvent} from "$src/utilities/EventDispatcher";
    import {RESOURCES} from "$src/api/schema/schema";

    let teams = $dataStore.teams;
    let projects: Project[] = [];
    let milestones: Milestone[] = [];
    let components: Component[] = [];
    let projectGroups: Group[] = [];
    let cycles: Cycle[] = [];
    let labels: Label[] = [];
    let statuses: Status[] = [];

    // let assignees = new Map<string, ..>();

    function updateForm() {
        console.log(`updateForm: ${formData.teamId}`);

        const team = formData.teamId ? $dataStoreApiClient.getTeam(formData.teamId) : undefined;
        if (team) {
            projects = $dataStoreApiClient.getProjectsByTeamId(team.id);
            cycles = team.cycleIds.map(id => $dataStoreApiClient.getCycle(id));
            statuses = team.workflow.statuses;
            labels = team.labelIds.map(id => $dataStoreApiClient.getLabel(id));
        }

        const project = formData.projectId ? $dataStoreApiClient.getProject(formData.projectId) : undefined;
        if (project) {
            milestones = project.milestoneIds.map(id => $dataStoreApiClient.getMilestone(id));
            components = project.componentIds.map(id => $dataStoreApiClient.getComponent(id));
            projectGroups = project.groupIds.map(id => $dataStoreApiClient.getGroup(id));
        }

        if (formData.statusId === undefined) {
            formData.statusId = statuses.find(status => status.name === 'Backlog')?.id;
        }
    }

    onMount(() => {
        console.log(form);

        form.title.focus();
    });

    // eslint-disable-next-line @typescript-eslint/ban-ts-comment
    // @ts-ignore
    $: updateForm(formData.teamId, formData.projectId);

    let form: HTMLFormElement;
    const formData = {
        teamId: $context.teamId,
        projectId: $context.projectId,
        title: undefined,
        description: undefined,
        statusId: undefined,
        cycleId: undefined,
        milestoneId: undefined,
        labelIds: [],
        groupIds: [],
        componentIds: [],
    }
    $: console.log('form.teamId:', formData.teamId);

    // TODO: Make this "feel" faster, directly update the DOM, process later, but do handle failures well.
    // We don't want users to lose their entered data in case of a failure.
    async function handleSubmit(event: SubmitEvent & { currentTarget: EventTarget & HTMLFormElement }) {
        event.preventDefault();
        const postedFormData = new FormData(event.currentTarget);
        const formDataAsJson = Object.fromEntries(
            Array.from(postedFormData).map(([key, value]) => {
                const parseJson = key.endsWith('{}');
                if (parseJson) {
                    key = key.slice(0, -2);
                    value = JSON.parse(value as string);
                }

                return [key, value];
            })
        );

        const result = await api.postTask(formDataAsJson);

        const createMore = postedFormData.has('create_more');
        if (createMore) {
            formData.title = '';
            formData.description = '';

            form.title.focus();
        } else {
            formModal.set(false);
        }

        dispatchDataChangeEvent({
            action: 'CREATE',
            resource_type: RESOURCES.TASK,
            resource: result,
        });
        createToast('success', `Task added`);
    }

    function handleKeyDown(event) {
        if ((event.ctrlKey || event.metaKey) && event.key === 'Enter') {
            event.stopPropagation();
            event.preventDefault();

            form.requestSubmit();
        }
    }

    // Ensure basic Flowbite components are rendered consistently
    setContext('background', false);
</script>

<svelte:window on:keydown={handleKeyDown}/>

<!-- svelte-ignore a11y-no-noninteractive-element-interactions -->
<form bind:this={form} id="create-task" method="POST" on:keydown={handleKeyDown}
      on:submit={handleSubmit}>
    <div class="space-y-5">
        <div class="flex items-center justify-between gap-x-2">
            <h3 class="text-xl font-semibold leading-6 text-custom-text-100">Create task</h3>
        </div>
        <div class="space-y-5">
            <div class="flex items-center gap-x-2">
                <TeamChoice bind:value="{formData.teamId}"
                            choices="{teams}"/>
                <ProjectChoice bind:value={formData.projectId}
                               baseComponent="{{teamId: formData.teamId}}"
                               choices="{[...projects.values()]}"
                />
            </div>
            <div class="space-y-3">
                <div class="mt-2 space-y-3">
                    <div>
                        <HtmlLabel class="mb-2">
                            <Input autocomplete="off" bind:value={formData.title} name="title" placeholder="Task title"
                                   required type="text"/>
                        </HtmlLabel>
                    </div>
                    <div>
                        <HtmlLabel class="mb-2">
                            <Textarea bind:value={formData.description} name="description"
                                      placeholder="Add description..." rows="4"/>
                        </HtmlLabel>
                    </div>
                    <div class="flex flex-wrap items-center gap-2">
                        <!--                        <Choice placeholder="Assigned to" choices="{assignees}" name="assignee"/>-->
                        <StatusChoice
                                value="{formData.statusId}"
                                choices="{statuses}"
                                required
                        />
                        <CycleChoice
                                choices="{[...cycles.values()]}"
                        />
                        <MilestoneChoice
                                baseComponent="{{projectId: formData.projectId}}"
                                choices="{[...milestones.values()]}"
                        />
                        <LabelsChoice
                                baseComponent="{{teamId: formData.teamId}}"
                                choices="{[...labels.values()]}"
                        />
                        <GroupsChoice
                                name="projectGroupIds"
                                baseComponent="{{projectId: formData.projectId}}"
                                choices="{[...projectGroups.values()]}"
                        />
                        <ComponentsChoice
                                baseComponent="{{projectId: formData.projectId}}"
                                choices="{[...components.values()]}"
                        />
                    </div>
                </div>
            </div>
        </div>
    </div>
</form>
<slot name="footer">
    <div class="-mx-5 mt-5 flex items-center justify-end border-t border-custom-border-200 px-5 pt-5 gap-5">
        <Toggle class="text-gray-500a" form="create-task" name="create_more" size="small">Create more</Toggle>
        <Button form="create-task" type="submit">Create task</Button>
    </div>
</slot>
