// tailwind.config.js

import defaultTheme from 'tailwindcss/defaultTheme';
import forms from '@tailwindcss/forms';
// Pastikan ini juga diimpor jika Anda menggunakan ES modules syntax
import tailwindcssFilters from 'tailwindcss-filters'; 

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
                sans: ['Figtree', ...defaultTheme.fontFamily.sans],
            },
            // Tempatkan konfigurasi backdropFilter di sini, di dalam extend
            backdropFilter: { 
                'none': 'none',
                'blur-md': 'blur(12px)',
                'blur-lg': 'blur(16px)',
                // Anda bisa tambahkan level blur lainnya jika dibutuhkan
                'blur-xl': 'blur(24px)',
                'blur-2xl': 'blur(32px)',
            },
        },
    },

    plugins: [
        tailwindcssFilters, // Gunakan variabel yang diimpor
        forms,
    ],
};