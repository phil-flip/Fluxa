import type {Command} from "prosemirror-state";
import {redo, undo} from "prosemirror-history"
import {chainCommands, exitCode} from "prosemirror-commands";
import type {Schema} from 'prosemirror-model';

const mac = typeof navigator != "undefined" ? /Mac|iP(hone|[oa]d)/.test(navigator.platform) : false

export class KeymapBuilder {
    public static readonly UNDO_REDO = 1;
    public static readonly NEWLINES = 2;
    public static readonly SAVE_ON_ENTER = 4;
    public static readonly SAVE_ON_MOD_ENTER = 8;

    public static build(configuration: number, schema: Schema) {
        const applicationKeyMap: { [key: string]: Command } = {};

        if (configuration & KeymapBuilder.UNDO_REDO) {
            applicationKeyMap["Mod-z"] = undo;
            applicationKeyMap["Shift-Mod-z"] = redo;

            if (!mac) {
                applicationKeyMap['Mod-y'] = redo;
            }
        }

        if (configuration & KeymapBuilder.NEWLINES) {
            const newlineCommand = chainCommands(exitCode, (state, dispatch) => {
                if (dispatch) {
                    dispatch(state.tr.replaceSelectionWith(schema.nodes.hard_break.create()).scrollIntoView());
                }

                return true;
            })
            applicationKeyMap['Shift-Enter'] = newlineCommand;

            if (mac) {
                applicationKeyMap['Ctrl-Enter'] = newlineCommand;
            }
        }

        if (configuration & KeymapBuilder.SAVE_ON_ENTER | KeymapBuilder.SAVE_ON_MOD_ENTER) {
            const saveCommand: Command = (state, dispatch, view) => {
                if (view) {
                    view.dom.blur();
                    return true;
                }
                return false;
            };
            applicationKeyMap['Mod-Enter'] = saveCommand;

            if (configuration & KeymapBuilder.SAVE_ON_ENTER) {
                applicationKeyMap['Enter'] = saveCommand;
            }
        }

        return applicationKeyMap;
    }
}
