<script lang="ts" context="module">
    export type CreateEditorView = (element: HTMLElement) => EditorView;
</script>
<script lang="ts">
    import {EditorView} from "prosemirror-view";

    export let createEditorView: CreateEditorView;

    let element: HTMLElement | undefined;
    let editorView: EditorView;

    $: if (element) {
        if (editorView) {
            console.debug('Existing editor found, will destroy that first');
            editorView.destroy();
        }
        console.debug('initializeEditor on: ', element);

        editorView = createEditorView(element);
    }
</script>

<style lang="scss">
    div {
        position: relative;
        margin-top: 1rem;
        font-size: .9rem;

        &:global(.title) {
            font-size: 1.25rem;
        }

        &:global(.placeholder:before) {
            position: absolute;
            top: 0;
            left: 0;
            content: attr(data-placeholder);
            color: #6B7280;
            pointer-events: none;
        }

        :global(p) {
            margin-bottom: 1rem;
        }
    }
</style>

<div bind:this={element} {...$$restProps}></div>
