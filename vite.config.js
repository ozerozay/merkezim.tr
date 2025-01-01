export default defineConfig({
    plugins: [
        laravel({
            input: ['resources/css/app.css', 'resources/js/app.js'],
            refresh: true,
        }),
    ],
    build: {
        outDir: 'public/tenancy/assets/build',
        emptyOutDir: true,
        manifest: true,
    },
});
