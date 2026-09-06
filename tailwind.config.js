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
                sans: ['Inter', 'ui-sans-serif', ...defaultTheme.fontFamily.sans],
                display: ['Manrope', 'Inter', 'ui-sans-serif', ...defaultTheme.fontFamily.sans],
            },
            colors: {
                ink: '#071018',
                panel: '#101b24',
                brand: {
                    50: '#fffbea',
                    100: '#fff3bd',
                    300: '#ffe071',
                    400: '#ffd449',
                    500: '#f5b918',
                    600: '#d89408',
                },
            },
            boxShadow: {
                glow: '0 24px 80px rgba(245, 185, 24, 0.12)',
            },
        },
    },

    plugins: [forms],
};
