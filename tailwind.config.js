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
                    50: '#fef2f1',
                    100: '#fee2e0',
                    200: '#fecac6',
                    300: '#fca49e',
                    400: '#f87066',
                    500: '#E63B2E',
                    600: '#d42a1d',
                    700: '#b22116',
                    800: '#931f16',
                    900: '#7a2019',
                    950: '#430c08',
                },
                dark: {
                    50: '#f6f6f6',
                    100: '#e7e7e7',
                    200: '#d1d1d1',
                    300: '#b0b0b0',
                    400: '#888888',
                    500: '#6d6d6d',
                    600: '#5d5d5d',
                    700: '#4f4f4f',
                    800: '#454545',
                    900: '#1a1a1a',
                    950: '#0d0d0d',
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
