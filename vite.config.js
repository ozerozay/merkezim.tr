import { defineConfig } from "vite";
import laravel from "laravel-vite-plugin";

export default defineConfig({
    plugins: [
        laravel({
            input: ["resources/css/app.css", "resources/js/app.js"],
            refresh: true
        })
    ],
    build: {
        outDir: "public/tenancy/assets/build", // Çıkış dizini
        assetsDir: "assets", // Varlıkların (CSS/JS) çıkış alt dizini
        manifest: true, // Manifest dosyasını oluşturur
        emptyOutDir: true // Build sırasında eski dosyaları temizler
    }
});
