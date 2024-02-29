<script setup>
import { ref } from "vue";
import { useAuthStore } from "../../../stores/auth";
const currentLanguage = ref("EN");
const languages = ["EN", "FR", "AR"];
const authStore = useAuthStore();
const changeLanguage = () => {
    localStorage.setItem("locale", currentLanguage.value);
}
</script>
<template>
    <div class="flex-nowrap items-center justify-center space-x-2 w-full">
        <!-- <DarkModeSwitch></DarkModeSwitch> -->
        <!-- <label for="languageSelect">Language:</label>
        <select
            id="languageSelect"
            v-model="currentLanguage"
            @change="changeLanguage"
            class="mr-4"
        >
            <option value="EN">English</option>
            <option value="FR">French</option>
            <option value="AR">Arabic</option>
        </select> -->
        <router-link to="/cart">
            <v-btn class="rounded-lg" variant="tonal">
                <v-icon
                    class="text-2xl"
                >mdi-cart</v-icon>
            </v-btn>
        </router-link>
        <router-link to="/wishlist" v-if="authStore.isLoggedIn">
            <v-btn class="rounded-lg" variant="tonal"
                >
                <v-icon
                    class="text-2xl"
                >mdi-heart</v-icon>
            </v-btn>
        </router-link>
    </div>
</template>
<style scoped></style>
