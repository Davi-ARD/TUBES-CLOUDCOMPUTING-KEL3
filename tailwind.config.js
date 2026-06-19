import defaultTheme from 'tailwindcss/defaultTheme';
import forms from '@tailwindcss/forms';

/** @type {import('tailwindcss').Config} */
export default {
    content: [
        './vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php',
        './storage/framework/views/*.php',
        './resources/views/**/*.blade.php',
    ],

    safelist: [
        'rotate-180',
        'translate-y-0',
        'opacity-100',
    ],

    theme: {
        extend: {
            colors: {
                'telkom-red':       '#E03A3E',
                'telkom-red-dark':  '#B82A2E',
                'telkom-red-light': '#F5B5B7',
                'telkom-gray':      '#58595B',
                'ink': {
                    900: '#1A1A1A',
                    700: '#343434',
                    500: '#666666',
                    300: '#BDBDBD',
                    100: '#F0F0F0',
                },
                'surface':       '#FFFFFF',
                'surface-alt':   '#F8F8F8',
                'surface-muted': '#EAEAEA',
            },
            fontFamily: {
                sans:    ['Poppins', 'Segoe UI', ...defaultTheme.fontFamily.sans],
                display: ['Poppins', 'Segoe UI', 'sans-serif'],
            },
            borderRadius: {
                'pill': '9999px',
            },
        },
    },

    plugins: [forms],
};
