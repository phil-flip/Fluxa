<style lang="scss">
    main {
        flex-grow: 1;
        overflow: hidden;
        position: relative;

        :global(> *:nth-child(1)) {
            flex-grow: 1;
            overflow: auto;
        }
    }
</style>

<main class="flex">
    <slot/>
    <slot name="sidebar"/>
</main>
