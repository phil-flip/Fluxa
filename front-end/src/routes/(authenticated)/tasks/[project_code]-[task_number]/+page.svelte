<script lang="ts" context="module">
    function updateProperty(currentTask: Task, updatedProperties: Partial<Task>): void {
        api.putTask(currentTask.id, updatedProperties);
    }
</script>

<script lang="ts">
    import Header from "$lib/layout/Header.svelte";
    import RightSideBar from "$lib/layout/RightSideBar.svelte";
    import Main from "$lib/layout/Main.svelte";
    import {page} from "$app/stores";
    import {Hr} from "flowbite-svelte";
    import {
        type Component,
        type Cycle,
        type Group,
        type Label,
        type Milestone,
        type Project,
        type Task,
        type Team
    } from "$src/api/schema/schema";
    import ProjectChoice from "$lib/form/Project.svelte";
    import ComponentsChoice from "$lib/form/Components.svelte";
    import GroupsChoice from "$lib/form/Groups.svelte";
    import CycleChoice from "$lib/form/Cycle.svelte";
    import MilestoneChoice from "$lib/form/Milestone.svelte";
    import LabelsChoice from "$lib/form/Labels.svelte";
    import StatusChoice from "$lib/form/Status.svelte";
    import {api} from "$src/api/ServerApiClient";
    import {dataStoreApiClient} from "$src/api/DataStoreApiClient";
    import SingleLineEditor from "$lib/form/SingleLineEditor.svelte";
    import MarkdownEditor from "$lib/form/MarkdownEditor.svelte";
    import {goto} from "$app/navigation";

    $: taskNumber = $page.params.task_number;
    $: projectCode = $page.params.project_code;

    $: project = $dataStoreApiClient.getProjectByCode(projectCode);
    let task: Task | undefined;
    let taskId: string | undefined;
    $: if (task && project && task.projectId !== project.id) {
        const project = $dataStoreApiClient.getProject(task.projectId);
        console.debug('The linked project has been changed. Updating the URL accordingly.');
        goto(`/tasks/${project.code}-${task.number}`, {replaceState: true});
    }
    $: if (dataStoreApiClientReady && taskNumber && project) {
        if (taskId) {
            task = $dataStoreApiClient.getTask(taskId);
        } else {
            task = $dataStoreApiClient.getTaskByNumberAndProjectId(parseInt(taskNumber), project.id);
            taskId = task.id;
        }
    }
    let team: Team | undefined;
    $: if (dataStoreApiClientReady && task?.teamId) {
        team = $dataStoreApiClient.getTeam(task.teamId)
    }
    let projects: Project[] = [];
    $: if (dataStoreApiClientReady && task?.teamId) {
        projects = $dataStoreApiClient.getProjectsByTeamId(task.teamId);
    }
    let milestones: Milestone[] = [];
    $: if (dataStoreApiClientReady && project) {
        milestones = project.milestoneIds.map(id => $dataStoreApiClient.getMilestone(id));
    }
    let components: Component[] = [];
    $: if (dataStoreApiClientReady && project) {
        components = project.componentIds.map(id => $dataStoreApiClient.getComponent(id));
    }
    let projectGroups: Group[] = [];
    $: if (dataStoreApiClientReady && project) {
        projectGroups = project.groupIds.map(id => $dataStoreApiClient.getGroup(id));
    }
    let cycles: Cycle[] = [];
    $:if (dataStoreApiClientReady && team) {
        cycles = team.cycleIds.map(id => $dataStoreApiClient.getCycle(id));
    }
    let labels: Label[] = [];
    $: if (dataStoreApiClientReady && team) {
        labels = team.labelIds!.map(id => $dataStoreApiClient.getLabel(id));
    }

    $: if (task && project &&
        (parseInt($page.params.task_number) !== task.number || $page.params.project_code !== project.code)) {
        goto(`/tasks/${project.code}-${task.number}`, {replaceState: true});
    }

    $: dataStoreApiClientReady = $dataStoreApiClient.ready;
</script>

<style lang="scss">
    .properties {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 1rem;
        padding: 1rem;
        align-items: center;
        color: #666666;

        > *:nth-child(2n+1) {
            font-weight: bold;
        }

        :global(button) {
            width: 100%;
        }

        hr {
            grid-column: span 2;
        }
    }

    input[name="title"] {
        width: 100%;
        border: none;
        box-shadow: none;
        padding: 0;
        cursor: text;
    }
</style>

<Header breadcrumb="{`${$page.params.project_code}-${$page.params.task_number}`}"/>

{#if task && project && team}
    <Main>
        <div class="m-4">
            <SingleLineEditor value={task.title}
                              onSave={value => {
                                api.putTask(task.id, {title: value});
                              }}
            />
            <MarkdownEditor value={task.description ?? ''}
                            data-placeholder="Add description.."
                            onSave={value => {
                                api.putTask(task.id, {description: value});
                            }}
            />

            <Hr classHr="my-8"/>
        </div>
        <RightSideBar>
            <div class="properties">
                <div>Status</div>
                <div>
                    <StatusChoice value={task.statusId} choices="{team.workflow.statuses}"
                                  on:change={(event)=>{
                                          updateProperty(task, {statusId: event.detail.value});
                                      }}/>
                </div>
                <div>Labels</div>
                <div>
                    <LabelsChoice value={$dataStoreApiClient.getTeamLabels(task.labelIds).map(label => label.id)}
                                  choices="{labels}"
                                  baseComponent="{{teamId: team.id}}"
                                  on:change={(event)=>{
                                          updateProperty(task, {labelIds: event.detail.values});
                                  }}/>
                </div>
                <div>Components</div>
                <div>
                    <ComponentsChoice value={task.componentIds}
                                      choices="{components}"
                                      baseComponent="{{projectId: project.id}}"
                                      on:change={(event)=>{
                                          updateProperty(task, {componentIds: event.detail.values});
                                      }}/>
                </div>
                <!--                <hr>-->
                <!--                <div>Team groups</div>-->
                <!--                <div>-->
                <!--                    <GroupsChoice value={(task.teamGroups ?? []).map(group => group.id)}-->
                <!--                                  choices="{team.groups ?? []}"-->
                <!--                                  baseComponent="{{teamId: team.id}}"-->
                <!--                                  label="Team groups"-->
                <!--                                  on:change={(event)=>{-->
                <!--                                          updateProperty(task, {teamGroupIds: event.detail.values}, {teamGroups: event.detail.choices});-->
                <!--                                      }}/>-->
                <!--                </div>-->
                <div>Groups</div>
                <div>
                    <GroupsChoice value={task.projectGroupIds}
                                  choices="{projectGroups}"
                                  baseComponent="{{projectId: project.id}}"
                                  on:change={(event)=>{
                                          updateProperty(task, {projectGroupIds: event.detail.values});
                                      }}/>
                </div>
                <!--                <div>Project labels</div>-->
                <!--                <div>-->
                <!--                    <LabelsChoice value={$dataStoreApiClient.getProjectLabels(task.labelIds).map(label => label.id)}-->
                <!--                                  choices="{labels}"-->
                <!--                                  baseComponent="{{projectId: project.id}}"-->
                <!--                                  on:change={(event)=>{-->
                <!--                                          updateProperty(task, {labelIds: event.detail.values}, {labels: event.detail.choices});-->
                <!--                                  }}/>-->
                <!--                </div>-->
                <div>Milestone</div>
                <div>
                    <MilestoneChoice value={task.milestoneId}
                                     choices="{milestones ?? []}"
                                     baseComponent="{{projectId: project.id}}"
                                     on:change={(event)=>{
                                         // TODO: project.milestones should also be updated, since there is one extra milestone after add.
                                         // This happened by reference at first, but we changed it to an assignment to trigger reactivity
                                         //         choices = [...choices, newChoice];
                                          updateProperty(task, {milestoneId: event.detail.value});
                                      }}/>
                </div>
                <div>Cycle</div>
                <div>
                    <CycleChoice value={task.cycleId}
                                 choices="{cycles}"
                                 on:change={(event)=>{
                                          updateProperty(task, {cycleId: event.detail.value});
                                      }}/>
                </div>
                <div>Project</div>
                <div>
                    {#if projects}
                        <ProjectChoice value={task.projectId}
                                       choices="{projects}"
                                       on:change={(event)=>{
                                          const projectId = event.detail.value;
                                          updateProperty(task, {projectId});
                                  }}/>
                    {/if}
                </div>
            </div>
        </RightSideBar>
    </Main>
{/if}
