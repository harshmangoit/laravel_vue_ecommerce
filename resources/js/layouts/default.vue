<script setup>
import NavBar from "../components/user/nav/NavBar.vue";
import TheFooter from "../components/user/TheFooter.vue";
/* Vuetify useTheme will not work with SSR enabled, more info - https://vuetify-nuxt-module.netlify.app/guide/server-side-rendering.html#vuetify-themes */
// import { useCustomTheme } from '~/composables/custom-theme'
// const { isDark } = useCustomTheme()

/* Update on using the above technique - We now use SSR HTTP Client hints technique instead of the above - https://vuetify-nuxt-module.netlify.app/guide/server-side-rendering.html#ssr-http-client-hints */
</script>
<template>
    <div>
        <!-- <div class="container mx-auto"> -->
        <v-app
            class="min-h-screen bg-white dark:bg-neutral-950 text-neutral-900 dark:text-neutral-50"
        >
            <!-- <v-app :theme="isDark ? 'dark' : 'light'"> -->
            <NavBar />
          
            <v-main class="py-2">
                <router-view></router-view>
            </v-main>
            <!-- https://vuetifyjs.com/en/features/application-layout/-->
            <TheFooter />
        </v-app>

        <!-- </div> -->
    </div>
</template>
<style></style>
