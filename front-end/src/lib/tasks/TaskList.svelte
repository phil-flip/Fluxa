<script>
    import TaskRow from "./TaskRow.svelte";

    export let title;
    export let tasks;
</script>

<style lang="scss">
  .grid-table {
    flex-grow: 1;
    display: grid;
    grid-auto-rows: max-content;
    grid-template-columns: repeat(4, min-content) minmax(auto, 1fr) repeat(1, min-content)
  }

  .grid-table .heading {
    grid-column: 1 / 7;
    font-weight: bold;
    background-color: #f0f0f0;
    border-top: 1px solid #ddd;
    border-bottom: 1px solid #ddd;
    padding: 0.5rem 1.5rem;
    display: flex;
    align-items: center;
    justify-content: space-between;
  }
</style>

<div aria-rowcount="{tasks.length}"
     class="grid-table text-sm text-gray-600 dark:text-gray-400 bg-white"
     role="grid">
    <div class="heading" tabindex="-1">
        <div>{title}</div>
        <div>
            <button class="
            text-sm font-semibold text-gray-800 dark:text-gray-200
            px-4 inline-flex justify-center items-center gap-2 rounded-md
            border border-transparent
            transition-all dark:focus:ring-offset-gray-800" data-hs-overlay="#modal"
                    type="button">
                +
            </button>
        </div>
    </div>
    {#each tasks as task, i}
        <TaskRow task="{task}" i="{i}"/>
    {/each}
</div>
