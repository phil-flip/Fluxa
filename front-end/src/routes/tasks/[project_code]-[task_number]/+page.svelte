<script lang="ts">
    import Header from "$lib/layout/Header.svelte";
    import RightSideBar from "$lib/layout/RightSideBar.svelte";
    import Main from "$lib/layout/Main.svelte";
    import {page} from "$app/stores";
    import {Hr} from "flowbite-svelte";
    import type {Component, Cycle, Group, Label, Milestone, Project, Task, Team} from "$src/api/schema/schema";
    import ProjectChoice from "$lib/form/Project.svelte";
    import ComponentsChoice from "$lib/form/Components.svelte";
    import GroupsChoice from "$lib/form/Groups.svelte";
    import CycleChoice from "$lib/form/Cycle.svelte";
    import MilestoneChoice from "$lib/form/Milestone.svelte";
    import LabelsChoice from "$lib/form/Labels.svelte";
    import StatusChoice from "$lib/form/Status.svelte";
    import {api} from "$src/api/ServerApiClient";
    import {dataStoreApiClient} from "$src/api/DataStoreApiClient";
    import {EditorState} from "prosemirror-state";
    import {EditorView} from "prosemirror-view";
    import {defaultMarkdownParser, defaultMarkdownSerializer, schema} from "prosemirror-markdown";
    import {baseKeymap} from "prosemirror-commands"
    import {keymap} from "prosemirror-keymap"
    import {placeholder} from "$src/editor/plugin/placeholder";
    import {createKeyMap} from "$src/editor/plugin/keymap";
    import {history} from "prosemirror-history"

    let descriptionEditorView: EditorView;

    function initializeEditor(task: Task) {
        console.debug('initializeEditor on: ', descriptionElement);

        if (descriptionEditorView) {
            console.debug('Existing editor found, will destroy that first');
            descriptionEditorView.destroy();
        }

        const state = EditorState.create({
                doc: defaultMarkdownParser.parse(task.description ?? ''),
                schema,
                plugins: [
                    placeholder(),
                    keymap(baseKeymap),
                    keymap(createKeyMap(schema)),
                    history(),
                ],
            })
        ;

        descriptionEditorView = new EditorView(descriptionElement, {
            state,
            handleDOMEvents: {
                'blur': () => {
                    const description = defaultMarkdownSerializer.serialize(descriptionEditorView.state.doc);

                    const currentDescription = task.description ?? '';
                    if (description !== currentDescription) {
                        api.putTask(task.id, {description: description});
                    }
                }
            },
            // dispatchTransaction(transaction) {
            //     const newState = descriptionEditorView.state.apply(transaction);
            //     descriptionEditorView.updateState(newState);
            //
            //     // Serialize the editor content to Markdown when it changes
            //     const markdownOutput = defaultMarkdownSerializer.serialize(newState.doc);
            //     console.log(markdownOutput); // Do something with the Markdown output
            // }
        });

        return () => {
            descriptionEditorView.destroy();
        };
    }

    $: if (descriptionElement && task) {
        initializeEditor(task);
    }

    function onChange(event: Event & { currentTarget: EventTarget & HTMLInputElement | HTMLTextAreaElement }) {
        const payload = {
            [event.currentTarget.name]: event.currentTarget.value
        }

        api.putTask(task.id, payload);
    }

    function updateProperty(currentTask: Task, updateTaskPayload: object, updatedProperties: Partial<Task>) {
        api.putTask(task.id, updateTaskPayload);
    }

    let task: Task;
    let project: Project;
    let team: Team;

    // Form values
    let projects: Project[];
    let milestones: Milestone[];
    let components: Component[];
    let projectGroups: Group[];
    let cycles: Cycle[];
    let labels: Label[];

    $: if ($dataStoreApiClient.ready) {
        project = $dataStoreApiClient.getProjectByCode($page.params.project_code);
        task = $dataStoreApiClient.getTaskByNumberAndProjectId(
            parseInt($page.params.task_number),
            project.id
        );
        team = $dataStoreApiClient.getTeam(task.teamId);

        projects = $dataStoreApiClient.getProjectsByTeamId(task.teamId);
        milestones = project.milestoneIds.map(id => $dataStoreApiClient.getMilestone(id));
        components = project.componentIds.map(id => $dataStoreApiClient.getComponent(id));
        projectGroups = project.groupIds.map(id => $dataStoreApiClient.getGroup(id));
        cycles = team.cycleIds.map(id => $dataStoreApiClient.getCycle(id));
        labels = team.labelIds!.map(id => $dataStoreApiClient.getLabel(id));
    }

    let descriptionElement: HTMLElement;

    $: ready = task && project;
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

  .description {
    position: relative;
    margin-top: 1rem;
    font-size: .9rem;

    &:global(.placeholder:before) {
      position: absolute;
      top: 0;
      left: 0;
      content: attr(data-placeholder);
      color: #6B7280;
      pointer-events: none;
    }

    :global(p) {
      margin-bottom: 1rem;
    }
  }
</style>

<Header breadcrumb="{`${$page.params.project_code}-${$page.params.task_number}`}"/>

{#if ready}
    <Main>
        <div class="m-4">
            <input type="text"
                   name="title"
                   on:change={onChange}
                   class="text-xl text-gray-900 dark:text-white w-full my-3"
                   value="{task.title}"
            />
            <div bind:this={descriptionElement} class="description"
                 data-placeholder="Add description.."></div>

            <Hr classHr="my-8"/>
        </div>
        <RightSideBar>
            <div class="properties">
                <div>Status</div>
                <div>
                    <StatusChoice value={task.statusId} choices="{team.workflow.statuses}"
                                  on:change={(event)=>{
                                          updateProperty(task, {statusId: event.detail.value}, {status: event.detail.choice});
                                      }}/>
                </div>
                <div>Labels</div>
                <div>
                    <LabelsChoice value={$dataStoreApiClient.getTeamLabels(task.labelIds).map(label => label.id)}
                                  choices="{labels}"
                                  baseComponent="{{teamId: team.id}}"
                                  on:change={(event)=>{
                                          updateProperty(task, {labelIds: event.detail.values}, {labels: event.detail.choices});
                                  }}/>
                </div>
                <div>Components</div>
                <div>
                    <ComponentsChoice value={task.componentIds}
                                      choices="{components}"
                                      baseComponent="{{projectId: project.id}}"
                                      on:change={(event)=>{
                                          updateProperty(task, {componentIds: event.detail.values}, {components: event.detail.choices});
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
                                          updateProperty(task, {projectGroupIds: event.detail.values}, {projectGroups: event.detail.choices});
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
                                          updateProperty(task, {milestoneId: event.detail.value}, {milestone: event.detail.choice});
                                      }}/>
                </div>
                <div>Cycle</div>
                <div>
                    <CycleChoice value={task.cycleId}
                                 choices="{cycles}"
                                 on:change={(event)=>{
                                          updateProperty(task, {cycleId: event.detail.value}, {cycle: event.detail.choice});
                                      }}/>
                </div>
                <div>Project</div>
                <div>
                    {#if projects}
                        <ProjectChoice value={task.projectId}
                                       choices="{projects}"
                                       on:change={(event)=>{
                                          updateProperty(task, {projectId: event.detail.value}, {project: event.detail.choice});
                                  }}/>
                    {/if}
                </div>
            </div>
        </RightSideBar>
    </Main>
{/if}
