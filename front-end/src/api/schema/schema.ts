export interface User {
    id: string;
    name: string;
    avatarUrl: string;
}

export interface Cycle {
    id: string;
    name: string;
}

export interface Milestone {
    id: string;
    sortOrder: number;
    name: string;
    projectId: string;
}

export interface NewMilestone {
    sortOrder: number;
    name: string;
    projectId: string;
}

export interface Component {
    id: string;
    name: string;
    projectId: string;
}

export interface NewComponent {
    name: string;
    projectId: string;
}

export interface Status {
    id: string;
    name: string;
    sortOrder: number;
}

export interface Group {
    id: string;
    name: string;
    projectId?: string;
    teamId?: string;
}

export interface NewGroup {
    name: string;
    projectId: string;
}

export interface Label extends NewLabel {
    id: string,
}

export interface NewLabel {
    name: string,
    properties: { [key: string]: unknown };
    projectId?: string;
    teamId?: string;
}

export interface NewLabel {
    name: string,
    properties: { [key: string]: unknown };
}

export interface NewProject extends BaseProject {
    teamId: string,
}

export interface BaseProject {
    name: string;
    code: string;
    memberIds: string[];
    labelIds: string[];
    groupIds: string[];
    milestoneIds: string[];
    componentIds: string[];
}

export interface Project extends BaseProject {
    id: string;
}

export interface Team {
    id: string;
    name: string;
    slug: string;
    options: { [key: string]: unknown };
    projectIds: string[];
    labelIds?: string[];
    groupIds?: string[];
    cycleIds?: string[];
    workflow: Workflow;
}

export interface Task {
    id: string,
    projectId: string,
    number: number,
    title: string,
    description: string,
    urgency: null | number,
    statusId: string,
    assigneeId: string,
    labelIds: string[],
    componentIds: string[],
    cycleId: string,
    milestoneId: string,
    projectGroupIds: string[],
    teamGroupIds: string[],
    teamId: string,
    createdAt: string,
    updatedAt?: string,
}

export interface Workflow {
    statuses: Status[];
}

export enum RESOURCES {
    COMPONENT,
    GROUP,
    PROJECT,
    CYCLE,
    LABEL,
    MILESTONE,
    TEAM,
    TASK,
}
