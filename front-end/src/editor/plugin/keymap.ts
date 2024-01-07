import type {Command} from "prosemirror-state";
import {redo, undo} from "prosemirror-history"
import {chainCommands, exitCode} from "prosemirror-commands";
import type {Schema} from 'prosemirror-model';

const mac = typeof navigator != "undefined" ? /Mac|iP(hone|[oa]d)/.test(navigator.platform) : false

export function createKeyMap(schema: Schema) {
    const applicationKeyMap: { [key: string]: Command } = {
        "Mod-z": undo,
        "Shift-Mod-z": redo,
    };

    const newlineCommand = chainCommands(exitCode, (state, dispatch) => {
        if (dispatch) {
            dispatch(state.tr.replaceSelectionWith(schema.nodes.hard_break.create()).scrollIntoView());
        }

        return true;
    })
    applicationKeyMap['Shift-Enter'] = newlineCommand;

    applicationKeyMap['Mod-Enter'] = (state, dispatch, view) => {
        if (view) {
            view.dom.blur();
            return true;
        }
        return false;
    };

    if (mac) {
        applicationKeyMap['Ctrl-Enter'] = newlineCommand;
    } else {
        applicationKeyMap['Mod-y'] = redo;
    }

    return applicationKeyMap;
}
