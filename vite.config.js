import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';
import tailwindcss from '@tailwindcss/vite';

export default defineConfig({
    plugins: [
        laravel({
            input: [
                'resources/css/app.css',
                'resources/js/app.js',
                'resources/js/dashboard-charts.js'
            ],
            refresh: true,
        }),
        tailwindcss(),
    ],
    server: {
        host: '10.8.18.81',
        port: 5173,
        hmr: {
            host: '10.8.18.81',
        },
    },
});
