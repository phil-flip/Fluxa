import type {
    Component,
    Cycle,
    Group,
    Label,
    Milestone,
    NewGroup,
    NewLabel,
    NewMilestone,
    NewProject,
    Project,
    Task,
    Team,
} from "$src/api/schema/schema";
import {RESOURCES} from "./schema/schema";
import {dispatchDataChangeEvent} from "$src/utilities/EventDispatcher";
import {browser} from '$app/environment';
import {goto} from "$app/navigation";

function getAuthorizationHeader(): { 'Authorization': string } {
    if (!browser) {
        throw new Error('Running from the server is not supported');
    }
    console.debug('Get authorization header');

    const token = localStorage.getItem('token');

    if (!token) {
        goto('/login');
    }

    return {'Authorization': `Bearer ${token}`};
}

/**
 * TODO: We use optimistic updating for our local data
 * but we DO have to revert these changes when the server-side call fails
 */
export class ServerApiClient {
    async authenticate(emailAddress: string, password: string): Promise<Response> {
        return await fetch(`https://api.localhost/auth`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'Accept': 'application/json',
            },
            body: JSON.stringify({
                emailAddress,
                password
            }),
        });
    }

    async putTask(id: string, taskData: Partial<Task>): Promise<void> {
        await fetch(`https://api.localhost/tasks/${id}`, {
            method: 'PATCH',
            headers: {
                'Content-Type': 'application/merge-patch+json',
                'Accept': 'application/json',
                ...getAuthorizationHeader(),
            },
            body: JSON.stringify(taskData),
        });

        dispatchDataChangeEvent({
            action: 'UPDATE',
            resource_type: RESOURCES.TASK,
            resource: taskData,
            id: id,
        });
    }

    async postComponent(component: Component): Promise<Component> {
        const response = await fetch(`https://api.localhost/components`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'Accept': 'application/json',
                ...getAuthorizationHeader(),
            },
            body: JSON.stringify(component),
        });

        const createdComponent = await response.json();

        dispatchDataChangeEvent({
            action: 'CREATE',
            resource_type: RESOURCES.COMPONENT,
            resource: createdComponent,
        });

        return createdComponent;
    }

    async postLabel(label: NewLabel): Promise<Label> {
        const response = await fetch(`https://api.localhost/labels`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'Accept': 'application/json',
                ...getAuthorizationHeader(),
            },
            body: JSON.stringify(label),
        });

        const createdLabel = await response.json();

        dispatchDataChangeEvent({
            action: 'CREATE',
            resource_type: RESOURCES.LABEL,
            resource: createdLabel,
        });

        return createdLabel;
    }

    async postMilestone(milestone: NewMilestone): Promise<Milestone> {
        const response = await fetch(`https://api.localhost/milestones`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'Accept': 'application/json',
                ...getAuthorizationHeader(),
            },
            body: JSON.stringify(milestone),
        });

        const createdMilestone = await response.json();

        dispatchDataChangeEvent({
            action: 'CREATE',
            resource_type: RESOURCES.MILESTONE,
            resource: createdMilestone,
        });

        return createdMilestone;
    }

    async postProject(project: NewProject): Promise<Project> {
        const response = await fetch(`https://api.localhost/projects`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'Accept': 'application/json',
                ...getAuthorizationHeader(),
            },
            body: JSON.stringify(project),
        });

        const createdProject = await response.json();

        dispatchDataChangeEvent({
            action: 'CREATE',
            resource_type: RESOURCES.PROJECT,
            resource: createdProject,
        });

        return createdProject;
    }

    async postGroup(group: NewGroup): Promise<Group> {
        const response = await fetch(`https://api.localhost/groups`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'Accept': 'application/json',
                ...getAuthorizationHeader(),
            },
            body: JSON.stringify(group),
        });

        const createdGroup = await response.json();

        dispatchDataChangeEvent({
            action: 'CREATE',
            resource_type: RESOURCES.GROUP,
            resource: createdGroup,
        });

        return createdGroup;
    }

    async resolveTeamSlug(slug: string) {
        return '5bcf6cd2-aeaa-4d25-80c0-ab4b285cbd24';
    }

    async getTasks(teamId?: string): Promise<Task[]> {
        const searchParameters = new URLSearchParams();
        if (teamId) {
            searchParameters.append('team_id', teamId);
        }
        const rows = await fetch(`https://api.localhost/tasks?${searchParameters}`, {
            headers: {
                'accept': 'application/json',
                ...getAuthorizationHeader(),
            },
        });

        return rows.json();
    }

    async getLabels(): Promise<Label[]> {
        const response = await fetch(`https://api.localhost/labels`, {
            method: 'GET',
            headers: {
                'Accept': 'application/json',
                ...getAuthorizationHeader(),
            },
        });

        return response.json();
    }

    async getMilestones(): Promise<Milestone[]> {
        const response = await fetch(`https://api.localhost/milestones`, {
            method: 'GET',
            headers: {
                'Accept': 'application/json',
                ...getAuthorizationHeader(),
            },
        });

        return response.json();
    }

    async getComponents(): Promise<Component[]> {
        const response = await fetch(`https://api.localhost/components`, {
            method: 'GET',
            headers: {
                'Accept': 'application/json',
                ...getAuthorizationHeader(),
            },
        });

        return response.json();
    }

    async getGroups(): Promise<Group[]> {
        const response = await fetch(`https://api.localhost/groups`, {
            method: 'GET',
            headers: {
                'Accept': 'application/json',
                ...getAuthorizationHeader(),
            },
        });

        return response.json();
    }

    async getProjects(): Promise<Project[]> {
        const response = await fetch(`https://api.localhost/projects`, {
            method: 'GET',
            headers: {
                'Accept': 'application/json',
                ...getAuthorizationHeader(),
            },
        });

        return response.json();
    }

    async getCycles(): Promise<Cycle[]> {
        const response = await fetch(`https://api.localhost/cycles`, {
            method: 'GET',
            headers: {
                'Accept': 'application/json',
                ...getAuthorizationHeader(),
            },
        });

        return response.json();
    }

    async postTask(task): Promise<Task> {
        const response = await fetch('https://api.localhost/tasks', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                ...getAuthorizationHeader(),
            },
            body: JSON.stringify(task)
        });

        task = response.json();

        dispatchDataChangeEvent({
            action: 'CREATE',
            resource_type: RESOURCES.TASK,
            resource: task,
        });

        return task;
    }

    async getTeams(): Promise<Team[]> {
        const rows = await fetch('https://api.localhost/teams', {
            headers: {
                'accept': 'application/json',
                ...getAuthorizationHeader(),
            }
        });

        return rows.json();
    }

    async getProject(code: string): Promise<Project> {
        const project = await fetch(`https://api.localhost/projects/${code}`, {
            headers: {
                'accept': 'application/json',
                ...getAuthorizationHeader(),
            }
        });

        return project.json();
    }
}

console.debug('export const api = new ServerApiClient();');
export const api = new ServerApiClient();
