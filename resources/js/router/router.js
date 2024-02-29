import { setupLayouts } from "virtual:generated-layouts";
import { createRouter, createWebHistory } from "vue-router";
import { isUserLoggedIn } from "./utils";
import routes from "~pages";
import { useAuthStore } from "../stores/auth";

console.log(routes);

export default function setupRouter(app) {
    const router = createRouter({
        history: createWebHistory(),
        routes: [
            {
                path: "/admin",
                redirect: () => ({ name: "admin-dashboard" }),
            },
            ...setupLayouts(routes),
        ],
    });

    router.beforeEach((to, _, next) => {
        // const authStore = useAuthStore();

        const isLoggedIn = isUserLoggedIn();
        // console.log(isLoggedIn);
        // console.log(to.meta.auth);
        if (to.meta.requiresAuth && !isLoggedIn) {
            return next({ name: "login" });
        } else {
            return next();
        }
    });

    app.use(router);
}
