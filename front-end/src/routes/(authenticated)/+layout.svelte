<script lang="ts">
    import {browser} from "$app/environment";
    import {goto} from "$app/navigation";
    import LayoutApplication from "$lib/layout/LayoutApplication.svelte";

    const token = localStorage.getItem('token');
    if (browser && !token) {
        console.debug('User not logged in. Redirecting to the login page.');

        goto('/login');
    }

    export let data;
</script>

<LayoutApplication>
    <slot></slot>
</LayoutApplication>
