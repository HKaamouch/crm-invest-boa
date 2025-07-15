import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';

export default defineConfig({
    plugins: [
        laravel({
            input: ['resources/css/app.css', 'resources/js/app.js', 'resources/js/investor-show.js', "resources/js/emails.js"],
            refresh: true,
        }),
    ],
    server: {
        host: '0.0.0.0',
        port: 5173,
        hmr: {
            host: 'localhost',
        },
        watch: {
            usePolling: true,
            interval: 1000,
        },
    },
    build: {
        outDir: 'public/build',
        manifest: 'manifest.json',
        assetsDir: 'assets',
    },
});
