import { defineConfig } from "vite";
import laravel from "laravel-vite-plugin";

export default defineConfig({
    plugins: [
        laravel({
            input: [
                "resources/css/app.css",
                "resources/js/app.js",
                "resources/js/fcm.js", // ✅ Pastikan ini ada
                "resources/js/bootstrap.js", // ✅ Dan ini juga
            ],
            refresh: true,
        }),
    ],
});
