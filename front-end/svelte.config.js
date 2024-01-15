import adapter from '@sveltejs/adapter-node';
import {vitePreprocess} from '@sveltejs/vite-plugin-svelte';
import * as path from "path";
import sequence from 'svelte-sequential-preprocessor'
import {preprocessMeltUI} from "@melt-ui/pp";

/** @type {import('@sveltejs/kit').Config} */
const config = {
    preprocess: sequence([
        vitePreprocess(),
        preprocessMeltUI(),
    ]),
    kit: {
        // adapter-auto only supports some environments, see https://kit.svelte.dev/docs/adapter-auto for a list.
        // If your environment is not supported or you settled on a specific environment, switch out the adapter.
        // See https://kit.svelte.dev/docs/adapters for more information about adapters.
        adapter: adapter(),
        alias: {
            '$src': path.resolve('./src'),
        },
    }
};

export default config;
