<script lang="ts">
    import "$src/app.scss";
    import {formModal} from "$src/stores";
    import {toastStore} from "$src/stores/ToastStore";
    import {Modal} from 'flowbite-svelte';
    import CreateTaskModal from "$lib/tasks/CreateTaskModal.svelte";
    import Toast from "$lib/Toast.svelte";
    import ContextMenu from "$lib/ContextMenu.svelte";
    import MainMenu from "./MainMenu/MainMenu.svelte";
</script>

<MainMenu/>

<div class="w-full pl-64 flex flex-col grow">
    <slot/>
</div>

<ContextMenu></ContextMenu>

<Modal autoclose={false} bind:open={$formModal} outsideclose>
    <CreateTaskModal/>
</Modal>

<div class="fixed bottom-0 right-0 mr-1">
    {#each $toastStore as {id, type, message}}
        <Toast id={id} type={type}>
            {@html message}
        </Toast>
    {/each}
</div>
