import {writable} from "svelte/store";
import type {Task} from "$src/api/schema/schema";

type ContextMenuData = {
    taskId: string;
};
export const contextMenuEvent = writable<PointerEvent | undefined>();
export const contextMenuData = writable<ContextMenuData>();
