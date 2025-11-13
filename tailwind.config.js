import defaultTheme from 'tailwindcss/defaultTheme';

/** @type {import('tailwindcss').Config} */
export default {
    content: [
        './vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php',
        './storage/framework/views/*.php',
        './resources/**/*.blade.php',
        './resources/**/*.js',
        './resources/**/*.vue',
    ],
    theme: {
        extend: {
            fontFamily: {
                // Set Plus Jakarta Sans as the default sans font
                'sans': ['Plus Jakarta Sans', ...defaultTheme.fontFamily.sans],
                // You can also create custom font families
                'heading': ['Plus Jakarta Sans', ...defaultTheme.fontFamily.sans],
                'body': ['Plus Jakarta Sans', ...defaultTheme.fontFamily.sans],
            },
            colors: {
                // Your custom colors
                'primary': {
                    50: '#eff6ff',
                    100: '#dbeafe', 
                    200: '#bfdbfe',
                    300: '#93c5fd',
                    400: '#60a5fa',
                    500: '#1593E6', // Your main color
                    600: '#1d4ed8',
                    700: '#1e40af',
                    800: '#1e3a8a',
                    900: '#1e3a8a',
                },
                'secondary': {
                    50: '#f9fafb',
                    100: '#f3f4f6',
                    200: '#e5e7eb',
                    300: '#d1d5db',
                    400: '#9ca3af',
                    500: '#DDDDDD', // Your secondary color
                    600: '#4b5563',
                    700: '#374151',
                    800: '#1f2937',
                    900: '#111827',
                }
            }
        },
    },
    plugins: [],
};
