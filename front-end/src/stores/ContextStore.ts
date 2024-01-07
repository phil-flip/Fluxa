import type {Writable} from "svelte/store";
import {writable} from "svelte/store";

export enum LAYOUTS {
    LIST = 'list',
    SWIMLANES = 'swimlanes',
    CALENDAR = 'calendar',
    SPREADSHEET = 'spreadsheet',
    GANTT = 'gantt',
}

export enum GROUPS {
    GROUP = 'GROUP',
    STATUS = 'STATUS',
    LABEL = 'LABEL',
    COMPONENT = 'COMPONENT',
    CYCLE = 'CYCLE',
    MILESTONE = 'MILESTONE',
    PROJECT = 'PROJECT',
    ASSIGNEE = 'ASSIGNEE',
}

export interface ContextStore {
    layout: LAYOUTS;
    groupBy: GROUPS;
    teamId?: string,
    projectId?: string,
}

const isBrowser = typeof window !== 'undefined';

const LOCAL_STORAGE_KEY = 'context';

function createContextStore(): Writable<ContextStore> {
    let store: Writable<ContextStore>;
    let context: ContextStore = {
        layout: LAYOUTS.LIST,
        groupBy: GROUPS.STATUS,
    };
    if (isBrowser) {
        const storedContext = localStorage.getItem(LOCAL_STORAGE_KEY);
        context = storedContext ? JSON.parse(storedContext) : context;

        store = writable(context);
        store.subscribe((value: ContextStore) => {
            localStorage.setItem(LOCAL_STORAGE_KEY, JSON.stringify(value));
        });
    } else {
        store = writable(context);
    }

    return store;
}

/**
 * Information about the current context, such as the team and project and tasks.
 */
export const context = createContextStore();


// TODO: Remove, this is not the right way
//
// dataStore.subscribe(({teams, tasks, error}: DataStore) => {
//     if (!error) {
//         context.update(context => {
//             context.tasks = tasks;
//             context.team = teams[0] ?? null;
//             if (context.team?.projects?.length) {
//                 context.project = context.team.projects[0];
//             }
//
//             return context;
//         });
//     }
// });
