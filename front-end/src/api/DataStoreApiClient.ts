import type {DataStore} from "$src/stores/DataStore";
import {dataStore} from "$src/stores/DataStore";
import type {Project} from "$src/api/schema/schema";
import {derived} from "svelte/store";
import {browser} from '$app/environment';

// TODO: handle datastore loading and error state
export class DataStoreApiClient {
    public readonly ready: boolean = false;

    constructor(private readonly dataStore: DataStore) {
        // TODO: Move to dataStore, that is more logical
        this.ready = browser && !this.dataStore.loading && !this.dataStore.error;
    }

    public getProjectsByTeamId(teamId: string): Project[] {
        const projectIds = new Set(
            this.dataStore.teams
                .filter(team => team.id === teamId)
                .flatMap(team => team.projectIds)
        );

        return Array.from(projectIds).map(projectId => this.getProject(projectId));
    }

    public resolveTeamSlug(slug: string): string {
        // debugger;
        const team = this.dataStore.teams.find(team => team.slug === slug);
        if (!team) {
            throw new Error(`Team with slug "${slug}" was not found`);
        }

        return team.id;
    }

    public resolveProjectCode(code: string): string {
        // debugger;
        const project = this.dataStore.projects.find(project => project.code === code);
        if (!project) {
            throw new Error(`Project with slug "${code}" was not found`);
        }

        return project.id;
    }

    public getProject(id: string) {
        return this.dataStore.projects.find(project => project.id === id);
    }

    public getProjectByCode(code: string) {
        return this.dataStore.projects.find(project => project.code === code);
    }

    public getMilestone(id: string) {
        return this.dataStore.milestones.find(milestone => milestone.id === id);
    }

    public getComponent(id: string) {
        return this.dataStore.components.find(components => components.id === id);
    }

    public getGroup(id: string) {
        return this.dataStore.groups.find(group => group.id === id);
    }

    public getLabel(id: string) {
        return this.dataStore.labels.find(label => label.id === id);
    }

    public getLabels(ids: string[]) {
        return ids.map(id => this.getLabel(id));
    }

    public getTeamLabels(ids: string[]) {
        return this.getLabels(ids).filter(label => label!.teamId);
    }

    public getProjectLabels(ids: string[]) {
        return this.getLabels(ids).filter(label => label!.projectId);
    }

    public getTeam(id: string) {
        return this.dataStore.teams.find(team => team.id === id);
    }

    public getTask(id: string) {
        return this.dataStore.tasks.find(task => task.id === id);
    }

    public getTaskByNumberAndProjectId(number: number, projectId: string) {
        return this.dataStore.tasks.find(task => task.number === number && task.projectId === projectId);
    }

    public getCycle(id: string) {
        return this.dataStore.cycles.find(cycle => cycle.id === id);
    }
}

export const dataStoreApiClient = derived(dataStore, api => new DataStoreApiClient(api));
