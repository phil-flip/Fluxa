/** @type {import('tailwindcss').Config} */
export default {
    content: [
        './src/**/*.{html,js,svelte,ts}',
        "./node_modules/flowbite-svelte/**/*.{html,js,svelte,ts}",
        './node_modules/preline/dist/*.js', // TODO: remove (replaced by tw elements)
    ],
    theme: {
        extend: {
            fontFamily: {
                sans: ["sans-serif"],
                body: ["sans-serif"],
            },
            colors: {
                primary: {
                    50: '#F2F5FF',
                    100: '#E6E9FF',
                    200: '#C0C9FF',
                    300: '#97A5FF',
                    400: '#6D80FF',
                    500: '#4F62FE',
                    600: '#384DEF',
                    700: '#2938EB',
                    800: '#1F2BCC',
                    900: '#131DA5',
                },
            },
        },
    },
    plugins: [
        require('@tailwindcss/forms'),
        require('flowbite/plugin'),
        require('preline/plugin'),
    ],
}
