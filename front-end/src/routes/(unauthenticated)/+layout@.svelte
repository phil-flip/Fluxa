<script lang="ts">
    import "$src/app.scss";
</script>

<style lang="scss">
    .box {
        margin: auto;
        display: flex;
        flex: initial;
        flex-direction: column;
        align-items: center;
    }
</style>

<div class="w-full bg-gradient-to-r from-blue-500/5 via-purple-500/5 to-pink-500/5">
    <slot></slot>
</div>
