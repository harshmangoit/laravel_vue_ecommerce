import { defineConfig } from "vite";
import laravel from "laravel-vite-plugin";
import Vue from "@vitejs/plugin-vue";
import Pages from "vite-plugin-pages";
import Layouts from "vite-plugin-vue-layouts";
// import vuetify from 'vuetify';

export default defineConfig({
    plugins: [
        Vue(),
        // vuetify({autoimport: true}),
        laravel({
            input: [
                "resources/css/app.css",
                "resources/js/app.js",
                "resources/js/main.js",
            ],
            refresh: true,
        }),
        Pages({
            dirs: ["resources/js/pages"],
        }),
        Layouts({ layoutsDirs: "resources/js/layouts" }),
    ],
});
