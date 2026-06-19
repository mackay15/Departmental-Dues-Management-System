import defaultTheme from 'tailwindcss/defaultTheme';
import forms from '@tailwindcss/forms';

/** @type {import('tailwindcss').Config} */
export default {
    content: [
        './vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php',
        './storage/framework/views/*.php',
        './resources/views/**/*.blade.php',
    ],

    theme: {
        extend: {
            fontFamily: {
                sans: ['Inter', 'Figtree', ...defaultTheme.fontFamily.sans],
            },
            colors: {
                azure: {
                    50: '#e8f4fd',
                    100: '#ADDFF1',
                    200: '#7ecae8',
                    300: '#4fb5df',
                    400: '#1a8fc4',
                    500: '#0d6ea3',
                    600: '#005580',
                    700: '#003f63',
                    800: '#003152',
                    900: '#002440',
                    950: '#001a2e',
                },
            },
        },
    },

    plugins: [forms],
};
