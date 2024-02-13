<script lang="ts">
    import {EditorView} from "prosemirror-view";
    import {EditorState} from "prosemirror-state";
    import {placeholder} from "$src/editor/plugin/placeholder";
    import {history} from "prosemirror-history";
    import Editor, {type CreateEditorView} from "$lib/form/Editor.svelte";
    import {Schema} from "prosemirror-model";
    import {keymap} from "prosemirror-keymap";
    import {baseKeymap} from "prosemirror-commands";
    import {KeymapBuilder} from "$src/editor/plugin/KeymapBuilder";

    export let value: string;
    export let onSave: (value: string) => void;

    const createEditorView: CreateEditorView = (element) => {
        const schema = new Schema({
            nodes: {
                doc: {
                    content: "text*"
                },
                text: {
                    inline: true
                }
            }
        });
        const state = EditorState.create({
            schema: schema,
            doc: schema.nodes.doc.create(null, schema.text(value)),
            plugins: [
                placeholder(),
                history(),
                keymap(KeymapBuilder.build(
                    KeymapBuilder.UNDO_REDO |
                    KeymapBuilder.NEWLINES |
                    KeymapBuilder.SAVE_ON_ENTER,
                    schema
                )),
                keymap(baseKeymap),
            ],
        });

        return new EditorView(element, {
            state,
            handleDOMEvents: {
                'blur': (view) => {
                    const newValue = view.state.doc.textContent;

                    if (newValue !== value) {
                        onSave(newValue);
                    }
                }
            },
        });
    }
</script>

<Editor class="title" createEditorView={createEditorView} {...$$restProps}/>
