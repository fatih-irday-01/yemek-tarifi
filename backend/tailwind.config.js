import defaultTheme from 'tailwindcss/defaultTheme';
import forms from '@tailwindcss/forms';

/** @type {import('tailwindcss').Config} */
export default {
    content: [
        './vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php',
        './storage/framework/views/*.php',
        './resources/views/**/*.blade.php',
        './resources/js/**/*.vue',
    ],

    theme: {
        extend: {
            fontFamily: {
                sans: ['Figtree', ...defaultTheme.fontFamily.sans],
            },
            colors: {
                primary: {
                    DEFAULT: '#2D6A4F',
                    light: '#40916C',
                    dark: '#1B4332',
                },
                accent: {
                    DEFAULT: '#95D5B2',
                    light: '#B7E4C7',
                    dark: '#74C69D',
                },
            },
        },
    },

    plugins: [forms],
};
