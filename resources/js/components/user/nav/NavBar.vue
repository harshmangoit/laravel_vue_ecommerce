<script setup>
import NavSecondary from "./NavSecondary.vue";
import ProfileActions from "./ProfileActions.vue";
import NavHamburger from "./NavHamburger.vue";
import NavPrimary from "./NavPrimary.vue";
import TheLogo from "./TheLogo.vue";
import { useAuthStore } from "../../../stores/auth";
import { useCartStore } from "../../../stores/cart";
import { useMobileMenu } from "../../../composables/mobile-menu";
import { ref } from "vue";
import { useRouter } from "vue-router";
const { isMobileMenuOpen } = useMobileMenu();

const router = useRouter();
const authStore = useAuthStore();
const cartStore = useCartStore();
const logout = () => {
    authStore.removeToken();
    authStore.removeUser();
    cartStore.clearCart();
    router.replace("/");
};

const searchText = ref("");
const searchOnEnter = () => {
    if (searchText.value.trim() !== "" && searchText.value.trim().length > 2) {
        const q = searchText.value;
        router.push({
            name: "search",
            query: { q },
        });
    }
};
</script>
<template>
    <v-container>
        <nav class="h-full navbar-grid">
            <div style="grid-area: logo" class="flex justify-center">
                <TheLogo />
            </div>
            <div
                data-pg-name="Hamburger"
                style="grid-area: hamburger"
                class="md:hidden"
            >
                <NavHamburger @click="isMobileMenuOpen = true"></NavHamburger>
            </div>
            <div
                data-pg-name="NavPrimary"
                style="grid-area: primary-nav"
                class="hidden md:flex"
            >
                <!-- <NavPrimary class="md:w-full" /> -->
            </div>
            <v-text-field
                v-model="searchText"
                @keyup.enter="searchOnEnter"
                label="Search..."
                append-inner-icon="i-material-symbols-search-rounded"
                hide-details
                variant="outlined"
                class="w-full h-1s"
                style="width: 25pc"
            ></v-text-field>
            <div data-pg-name="Profile" class="flex md:items-center">
                <router-link to="/login"
                    ><v-btn
                        v-if="!authStore.isLoggedIn"
                        variant="tonal"
                        class="ml-3"
                        >Sign In</v-btn
                    ></router-link
                >

                <v-menu>
                    <template #activator="{ props }">
                        <v-btn
                            v-if="authStore.isLoggedIn"
                            text="Click me"
                            variant="tonal"
                            v-bind="props"
                            class="md:ml-2"
                            label="Login"
                            aria-label="Avatar"
                            icon
                        >
                            <v-icon size="large">mdi-account</v-icon>
                        </v-btn>
                    </template>
                    <v-card min-width="300">
                        <v-list>
                            <v-list-item>
                                <div
                                    class="flex flex-col items-center my-1 space-x-1 w-full"
                                >
                                    <ProfileActions class="flex md:hidden" />
                                    <v-btn
                                        v-if="authStore.isLoggedIn"
                                        @click="logout"
                                        class="font-bold my-4 w-fit md:my-2"
                                        >Logout</v-btn
                                    >
                                </div>
                            </v-list-item>
                        </v-list>
                        <v-list>
                            <NavSecondary />
                        </v-list>
                    </v-card>
                </v-menu>
                <ProfileActions class="hidden md:flex ml-2" />
            </div>
        </nav>
        <v-navigation-drawer
            v-model="isMobileMenuOpen"
            name="drawer"
            data-pg-name="NavSecondary"
            style="grid-area: primary-nav"
            class="w-80 md:hidden"
            location="left"
            temporary
        >
            <NavPrimary class="m-4" />
        </v-navigation-drawer>
    </v-container>
</template>
<style scoped>
.navbar-grid {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 1rem;
    background-color: #fff;
}
@media (min-width: 768px) {
    .navbar-grid {
        display: grid;
        grid-template-columns: auto auto auto;
        grid-template-rows: auto auto;
        grid-template-areas: "logo search profile" "primary-nav primary-nav primary-nav";
        gap: 20px;
    }
}
@media (min-width: 1280px) {
    .navbar-grid {
        display: grid;
        grid-template-columns: auto auto auto auto;
        grid-template-rows: auto;
        grid-template-areas: "logo primary-nav search profile";
    }
}
</style>
