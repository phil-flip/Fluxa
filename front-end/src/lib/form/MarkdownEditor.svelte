<script lang="ts">
    import {EditorView} from "prosemirror-view";
    import {EditorState} from "prosemirror-state";
    import {defaultMarkdownParser, defaultMarkdownSerializer, schema} from "prosemirror-markdown";
    import {placeholder} from "$src/editor/plugin/placeholder";
    import {keymap} from "prosemirror-keymap";
    import {baseKeymap} from "prosemirror-commands";
    import {KeymapBuilder} from "$src/editor/plugin/KeymapBuilder";
    import {history} from "prosemirror-history";
    import Editor, {type CreateEditorView} from "$lib/form/Editor.svelte";

    export let value: string;
    export let onSave: (value: string) => void;

    const createEditorView: CreateEditorView = (element) => {
        const state = EditorState.create({
            doc: defaultMarkdownParser.parse(value),
            schema,
            plugins: [
                placeholder(),
                keymap(baseKeymap),
                keymap(KeymapBuilder.build(
                    KeymapBuilder.UNDO_REDO |
                    KeymapBuilder.NEWLINES |
                    KeymapBuilder.SAVE_ON_MOD_ENTER,
                    schema
                )),
                history(),
            ],
        });

        return new EditorView(element, {
            state,
            handleDOMEvents: {
                'blur': (view) => {
                    const newValue = defaultMarkdownSerializer.serialize(view.state.doc);

                    if (newValue !== value) {
                        onSave(newValue);
                    }
                }
            },
        });
    }
</script>

<Editor createEditorView={createEditorView} {...$$restProps}/>
