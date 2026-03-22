import defaultTheme from 'tailwindcss/defaultTheme';
import forms from '@tailwindcss/forms';
import typography from '@tailwindcss/typography';

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
            colors: {
                brand: {
                    50: '#fff5f4',
                    100: '#ffe0dd',
                    200: '#ffc7c2',
                    300: '#ff9e96',
                    400: '#f0695e',
                    500: '#dc4535',
                    600: '#c33628',
                    700: '#a32c21',
                    800: '#872720',
                    900: '#6f2520',
                    950: '#3c0f0b',
                },
                dark: {
                    50: '#f5f5f4',
                    100: '#e6e5e3',
                    200: '#cccbc8',
                    300: '#a8a6a2',
                    400: '#78756f',
                    500: '#5e5b56',
                    600: '#4d4a46',
                    700: '#3a3836',
                    800: '#2a2927',
                    900: '#1c1b1a',
                    950: '#111110',
                },
            },
            fontFamily: {
                sans: ['Inter', ...defaultTheme.fontFamily.sans],
                display: ['Montserrat', ...defaultTheme.fontFamily.sans],
            },
        },
    },
    plugins: [forms, typography],
};
