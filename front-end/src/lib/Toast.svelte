<script lang="ts">
    import {Toast} from 'flowbite-svelte';
    import {slide} from 'svelte/transition';
    import {CheckSquare2} from "lucide-svelte";

    export let type: string;

    let color = undefined;
    if (type === 'success') {
        color = 'green';
    }
</script>

<div transition:slide|local>
    <Toast {...$$restProps} class="shadow shadow-blue-700 flex gap-10 min-w-[250px] mb-1" color={color}
           dismissable={false}>
        <svelte:fragment slot="icon">
            {#if type === 'success'}
                <CheckSquare2 class="w-5 h-5"/>
                <span class="sr-only">Check icon</span>
            {/if}
        </svelte:fragment>
        <slot/>
    </Toast>
</div>
