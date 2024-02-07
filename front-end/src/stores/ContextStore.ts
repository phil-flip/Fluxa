import type {Writable} from "svelte/store";
import {get, writable} from "svelte/store";
import type {DataStore} from "$src/stores/DataStore";
import {dataStore} from "$src/stores/DataStore";
import {browser} from "$app/environment";

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
    NO_GROUPING = 'NO_GROUPING',
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

if (browser) {
    dataStore.subscribe(({teams, loading, error}: DataStore) => {
        const contextValue = get(context);
        if (!error && !loading && !contextValue.teamId && teams && teams!.length > 0) {
            context.update(context => {
                context.teamId = teams![0].id ?? null;
                if (context.teamId) {
                    context.projectId = teams![0].projectIds[0];
                }

                return context;
            });
        }
    });
}
