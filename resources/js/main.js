import { createApp } from "vue";
import { createI18n } from "vue-i18n";
import { createPinia } from "pinia";
import App from "./app.vue";
import setupRouter from "./router/router";
import "../css/app.css";
import EN from "./locale/en.json";
import FR from "./locale/fr.json";
import AR from "./locale/ar.json";
import { useAuthStore } from "./stores/auth";
// Vuetify
import "vuetify/styles";
import { createVuetify } from "vuetify";
import * as components from "vuetify/components";
import * as directives from "vuetify/directives";
import vue3GoogleLogin from "vue3-google-login";


const pinia = createPinia();

const vuetify = createVuetify({
    components,
    directives,
});

const app = createApp(App);

const CLIENT_ID = "897640949480-kq9nt5kqtj4p5ugp2ns20mrqrilruc79.apps.googleusercontent.com"

app.use(vue3GoogleLogin, {
    clientId: CLIENT_ID,
});

app.use(pinia);
const authStore = useAuthStore();
const i18n = createI18n({
    locale: authStore.locale,
    messages: {
        EN: EN,
        FR: FR,
        AR: AR,
    },
});
app.use(vuetify);
app.use(i18n);

setupRouter(app);

app.mount("#app");
