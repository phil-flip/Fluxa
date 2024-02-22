<script lang="ts">
    export let useWebsiteFont = true;
</script>

<style lang="scss">
    .website {
        font-family: "Raleway", serif;
    }
</style>

<div class:website={useWebsiteFont} class="min-h-dvh flex flex-col w-full bg-gradient-to-b from-white to-blue-500/15">
    <slot></slot>
</div>
