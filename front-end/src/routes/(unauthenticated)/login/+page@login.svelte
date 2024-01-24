<script lang="ts">
    import {api} from "$src/api/ServerApiClient";
    import {goto} from '$app/navigation';

    let isSubmitted = false;
    const login = async () => {
        isSubmitted = true;
        const response = await api.authenticate(username, password);
        isSubmitted = false;

        if (response.status === 200) {
            const responseJson = await response.json();
            localStorage.setItem('token', responseJson.token);
            console.debug('Set token');

            goto('/');
        } else {
            error = 'Invalid credentials';
        }
    }

    let username: string;
    let password: string;
    let error: string = '';
</script>

<div class="max-w-[30rem] px-4 py-10 sm:px-6 lg:px-8 lg:py-[15rem] mx-auto">
    <div class="bg-white rounded-xl shadow p-4 sm:p-7 dark:bg-slate-900">
        <div class="text-center mb-8">
            <h2 class="text-2xl md:text-3xl font-bold text-gray-800 dark:text-gray-200">
                Log in to Fluxa
            </h2>
            <p class="text-sm text-gray-600 dark:text-gray-400 italic">
                Let's get things done!
            </p>
        </div>

        <form on:submit={login}>
            <input bind:value={username}
                   class="mb-2 py-2 px-3 pe-11 block w-full border-gray-200 shadow-sm text-sm rounded-lg focus:border-blue-500 focus:ring-blue-500 disabled:opacity-50 disabled:pointer-events-none dark:bg-slate-900 dark:border-gray-700 dark:text-gray-400 dark:focus:ring-gray-600"
                   placeholder="Email address"
                   required
                   type="text">
            <input bind:value={password}
                   class="py-2 px-3 pe-11 block w-full border-gray-200 shadow-sm text-sm rounded-lg focus:border-blue-500 focus:ring-blue-500 disabled:opacity-50 disabled:pointer-events-none dark:bg-slate-900 dark:border-gray-700 dark:text-gray-400 dark:focus:ring-gray-600"
                   placeholder="Password"
                   required
                   type="password">

            {#if error}
                <div class="mt-2 text-sm text-red-600">{error}</div>
            {/if}

            <div class="mt-5 flex justify-end gap-x-2">
                <button
                    disabled="{isSubmitted}"
                    class="w-full justify-center py-2 px-3 inline-flex items-center gap-x-2 text-sm font-semibold rounded-lg border border-transparent bg-blue-600 text-white hover:bg-blue-700 disabled:opacity-50 disabled:pointer-events-none dark:focus:outline-none dark:focus:ring-1 dark:focus:ring-gray-600"
                    type="submit">
                    Log in
                </button>
            </div>
        </form>
    </div>
</div>

