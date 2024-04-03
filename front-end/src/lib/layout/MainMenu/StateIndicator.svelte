<script lang="ts">
    export let state: boolean;
</script>

{#if state}
    <svg
        class="ml-auto block w-3 h-3 text-gray-600 group-hover:text-gray-500 dark:text-gray-400"
        width="16" height="16" viewBox="0 0 16 16" fill="none"
        xmlns="http://www.w3.org/2000/svg">
        <path
            d="M2 11L8.16086 5.31305C8.35239 5.13625 8.64761 5.13625 8.83914 5.31305L15 11"
            stroke="currentColor" stroke-width="2" stroke-linecap="round"></path>
    </svg>
{:else}
    <svg
        class="ml-auto block w-3 h-3 text-gray-600 group-hover:text-gray-500 dark:text-gray-400"
        width="16" height="16" viewBox="0 0 16 16" fill="none"
        xmlns="http://www.w3.org/2000/svg">
        <path
            d="M2 5L8.16086 10.6869C8.35239 10.8637 8.64761 10.8637 8.83914 10.6869L15 5"
            stroke="currentColor" stroke-width="2" stroke-linecap="round"></path>
    </svg>
{/if}
