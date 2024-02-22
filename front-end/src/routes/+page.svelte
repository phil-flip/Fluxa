<script lang="ts">
    import LayoutApplication from "$lib/layout/LayoutApplication.svelte";
    import PageApplicationRoot from "$lib/page/PageApplicationRoot.svelte";
    import {browser} from "$app/environment";
    import {PUBLIC_ABOUT_FULLY_QUALIFIED_DOMAIN_NAME} from "$env/static/public";

    if (browser && !localStorage.getItem('token')) {
        window.location.href = `https://${PUBLIC_ABOUT_FULLY_QUALIFIED_DOMAIN_NAME}`;
    }
</script>

{#if browser && localStorage.getItem('token')}
    <LayoutApplication>
        <PageApplicationRoot/>
    </LayoutApplication>
{/if}
