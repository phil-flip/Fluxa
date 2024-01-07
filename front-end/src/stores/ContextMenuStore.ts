import {writable} from "svelte/store";
import type {Task} from "$src/api/schema/schema";

type ContextMenuData = {
    task: Task;
};
export const contextMenuEvent = writable<PointerEvent | undefined>();
export const contextMenuData = writable<ContextMenuData>();
