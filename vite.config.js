import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';

export default defineConfig({
    optimizeDeps: {
        include: ['datatables.net-dt'],
    },
    build: {
        chunkSizeWarningLimit: 1000,
    },
    plugins: [
        laravel({
            input: ['resources/css/app.css', 'resources/js/app.js'],
            refresh: true,
        }),
    ],
    // server: {
    //     host: '0.0.0.0', // listen on all addresses
    //     hmr: {
    //         host: '192.168.100.10', // 👈 replace with your machine's LAN IP
    //     },
    // },
});
