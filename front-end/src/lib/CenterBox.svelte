<style lang="scss">
    .wrapper {
        display: flex;
        flex-grow: 1;
        justify-items: center;
        justify-content: center;
        align-items: center;

        .content {
            box-shadow: rgba(0, 0, 0, 0.07) 0px 4px 44px;
            border: 1px solid rgb(239, 241, 244);
            border-radius: 8px;
            padding: 3rem;
            width: 450px;
            height: auto;

            :global(p) {
                color: gray;
            }
        }
    }
</style>

<div class="wrapper">
    <div class="content">
        <slot/>
    </div>
</div>
