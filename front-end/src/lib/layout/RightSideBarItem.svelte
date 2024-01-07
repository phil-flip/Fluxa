<script>
    export let name;
    export let index;
</script>

<div class="border-b border-gray-200 p-4">
    <button class="w-full hs-collapse-toggle open py-3 px-4 inline-flex justify-between items-center
        gap-2 font-semibold text-gray-500 hover:bg-gray-100
        focus:outline-none focus:ring-0 focus:ring-gray-200 transition-all text-sm dark:focus:ring-offset-gray-800" data-hs-collapse="#hs-basic-collapse-heading{index}"
            id="hs-basic-collapse" type="button">
        {name}
        <svg class="hs-collapse-open:rotate-180 w-2.5 h-2.5" fill="none" height="16" viewBox="0 0 16 16"
             width="16" xmlns="http://www.w3.org/2000/svg">
            <path d="M2 5L8.16086 10.6869C8.35239 10.8637 8.64761 10.8637 8.83914 10.6869L15 5"
                  stroke="currentColor" stroke-linecap="round" stroke-width="2"/>
        </svg>
    </button>
    <div aria-labelledby="hs-basic-collapse"
         class="hs-collapse w-full overflow-hidden transition-[height] duration-300
text-gray-500 dark:text-gray-400
py-2 px-4"
         id="hs-basic-collapse-heading{index}">
        <slot/>
    </div>
</div>
