import {writable} from 'svelte/store';

const timeoutInMilliseconds = 6000;

export const toastStore = writable([]);

export function createToast(type, message) {
    const id = new Date().getTime();
    toastStore.update(allToasts => [...allToasts, {id, type, message}]);

    setTimeout(() => {
        remove(id);
    }, timeoutInMilliseconds);
}

function remove(id) {
    toastStore.update(allToasts => allToasts.filter(t => {
        return t.id !== id;
    }));
}
