import type {Invalidator, Subscriber, Unsubscriber, Writable} from "svelte/store";
import {writable} from "svelte/store";
import type {Component, Cycle, Group, Label, Milestone, Project, Task, Team} from "$src/api/schema/schema";
import {RESOURCES} from "$src/api/schema/schema";
import type {ServerApiClient} from "$src/api/ServerApiClient";
import {api} from "$src/api/ServerApiClient";
import {browser} from "$app/environment";

function addEventListeners() {
    // @ts-ignore
    document.addEventListener('data-change', (event: CustomEvent<DataChangeEventDetail>) => {
        const detail = event.detail;

        if (detail.resource_type === RESOURCES.TASK && detail.action === 'UPDATE') {
            updateTask(detail.id as string, detail.resource as Partial<Task>);
        } else if (detail.resource_type === RESOURCES.TASK && detail.action === 'CREATE') {
            addTask(detail.resource as Task);
        } else if (detail.resource_type === RESOURCES.COMPONENT && detail.action === 'CREATE') {
            addComponent(detail.resource as Component);
        } else if (detail.resource_type === RESOURCES.LABEL && detail.action === 'CREATE') {
            addLabel(detail.resource as Label);
        } else if (detail.resource_type === RESOURCES.MILESTONE && detail.action === 'CREATE') {
            addMilestone(detail.resource as Milestone);
        } else if (detail.resource_type === RESOURCES.GROUP && detail.action === 'CREATE') {
            addGroup(detail.resource as Group);
        } else if (detail.resource_type === RESOURCES.PROJECT && detail.action === 'CREATE') {
            addProject(detail.resource as Project);
        } else if (detail.resource_type === RESOURCES.TEAM && detail.action === 'CREATE') {
            addTeam(detail.resource as Team);
        } else {
            throw new Error(`No handler found for data change event with detail: ${JSON.stringify(detail)}`);
        }
    });
}

function addLabel(label: Label) {
    dataStore.update(dataStore => {
        if (label.projectId) {
            let projectIndex = dataStore.projects.findIndex(project => project.id === label.projectId);
            dataStore.projects[projectIndex].labelIds = [...dataStore.projects[projectIndex].labelIds, label.id];
        } else {
            let teamIndex = dataStore.teams.findIndex(team => team.id === label.teamId);
            dataStore.teams[teamIndex].labelIds = [...dataStore.teams[teamIndex].labelIds, label.id];
        }

        dataStore.labels = [...dataStore.labels, label];

        return dataStore;
    });
}

function addGroup(group: Group) {
    dataStore.update(dataStore => {
        let projectIndex = dataStore.projects.findIndex(project => project.id === group.projectId);
        dataStore.projects[projectIndex].groupIds = [...dataStore.projects[projectIndex].groupIds, group.id];
        dataStore.groups = [...dataStore.groups, group];

        return dataStore;
    });
}

function addProject(project: Project) {
    dataStore.update(dataStore => {
        project.teamIds.forEach((teamId: string) => {
            const teamIndex = dataStore.teams!.findIndex(team => team.id === teamId);
            dataStore.teams![teamIndex].projectIds = [...dataStore.teams![teamIndex].projectIds, project.id];
        });
        dataStore.projects = [...dataStore.projects, project];

        return dataStore;
    });
}

function addTeam(team: Team) {
    dataStore.update(dataStore => {
        dataStore.teams = [...dataStore.teams, team];

        return dataStore;
    });
}

function addComponent(component: Component) {
    dataStore.update(dataStore => {
        let projectIndex = dataStore.projects.findIndex(project => project.id === component.projectId);
        dataStore.projects[projectIndex].componentIds = [...dataStore.projects[projectIndex].componentIds, component.id];
        dataStore.components = [...dataStore.components, component];

        return dataStore;
    });
}

function addMilestone(milestone: Milestone) {
    dataStore.update(dataStore => {
        let projectIndex = dataStore.projects.findIndex(project => project.id === milestone.projectId);
        dataStore.projects[projectIndex].milestoneIds = [...dataStore.projects[projectIndex].milestoneIds, milestone.id];
        dataStore.milestones = [...dataStore.milestones, milestone];

        return dataStore;
    });
}

function addTask(task: Task) {
    dataStore.update(dataStore => {
        dataStore.tasks = [...dataStore.tasks, task];

        return dataStore;
    });
}

function updateTask(id: string, taskData: Partial<Task>) {
    dataStore.update(dataStore => {
        let index = dataStore.tasks.findIndex(task => task.id === id);

        if (index === -1) {
            throw new Error(`Task with id "${id}" was not found.`);
        }
        // TODO: Improve this mechanism, because this causes reactivity all across the application
        // because we are updating (assigning) the whole task
        dataStore.tasks[index] = {
            ...dataStore.tasks[index],
            ...taskData,
        };

        return dataStore;
    });
}

export interface DataStore {
    components?: Component[];
    groups?: Group[];
    projects?: Project[];
    cycles?: Cycle[];
    labels?: Label[];
    milestones?: Milestone[];
    teams?: Team[];
    tasks?: Task[];
    loading: boolean;
    error: null | string;
}

let initialized = false;

function createDataStore(api: ServerApiClient): Writable<DataStore> {
    const store = writable<DataStore>({
        loading: false,
        error: null,
    });

    async function load() {
        store.update(data => {
            data.loading = true;

            return data;
        });

        try {
            const [
                teams,
                projects,
                tasks,
                components,
                groups,
                cycles,
                labels,
                milestones,
            ] = await Promise.all([
                api.getTeams(),
                api.getProjects(),
                api.getTasks(),
                api.getComponents(),
                api.getGroups(),
                api.getCycles(),
                api.getLabels(),
                api.getMilestones(),
            ]);
            store.set({
                teams, projects, tasks, components, groups, cycles, labels, milestones,
                loading: false,
                error: null
            });

            console.info('Loading initial data succeeded');
        } catch (error) {
            console.error('Loading initial data failed', error);

            store.set({loading: false, error: error.message});
        }
    }

    return {
        ...store,
        subscribe(run: Subscriber<T>, invalidate?: Invalidator<T>): Unsubscriber {
            if (browser && localStorage.getItem('token') && !initialized) {
                console.debug('Loading initial data started');

                initialized = true;

                load().then(() => {
                    addEventListeners();
                });
            }

            return store.subscribe(run, invalidate);
        },
    };
}

console.debug('export const dataStore: Writable<DataStore> = createDataStore(api);');
export const dataStore: Writable<DataStore> = createDataStore(api);
