import {Plugin} from "prosemirror-state";
import type {EditorView} from "prosemirror-view";

const updateHandler = (view: EditorView) => {
    const originalElement = view.dom.parentElement as HTMLElement;

    if (view.state.doc.textContent) {
        originalElement.classList.remove('placeholder');
    } else {
        originalElement.classList.add('placeholder');
    }
}

export function placeholder() {
    return new Plugin({
        view(view) {
            updateHandler(view);

            return {
                update: updateHandler
            };
        }
    })
}