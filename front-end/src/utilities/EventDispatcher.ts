import type {RESOURCES} from "$src/api/schema/schema";

export const DATA_CHANGE_EVENT_NAME = 'data-change';

export interface DataChangeEventDetail {
    resource_type: RESOURCES,
    action: 'CREATE' | 'DELETE' | 'UPDATE'
    id?: string,
    resource?: object,
}

export function dispatchDataChangeEvent(detail: DataChangeEventDetail) {
    const event = new CustomEvent<DataChangeEventDetail>(DATA_CHANGE_EVENT_NAME, {detail});
    document.dispatchEvent(event);
}
