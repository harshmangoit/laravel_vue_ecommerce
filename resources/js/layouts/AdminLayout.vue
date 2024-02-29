<template>
    <v-app>
        <!-- Sidebar -->
        <v-navigation-drawer app v-model="drawer">
            <v-list>
                <v-list-item>
                    <!-- <v-list-item-icon> -->
                    <v-icon>mdi-view-dashboard</v-icon>
                    <br />
                    <br /><br />
                    <h4 class="text-center">Admin</h4>
                    <ul class="menu-list">
                        <br />
                        <hr />
                        <li class="py-4">
                            <router-link to="/admin/product"
                                >Products</router-link
                            >
                        </li>
                        <li class="py-4">
                            <router-link to="/admin/category"
                                >Category</router-link
                            >
                        </li>
                        <li class="py-4">
                            <router-link to="/admin/order">Orders</router-link>
                        </li>
                        <li class="py-4">
                            <router-link to="/admin/invoice">Invoices</router-link>
                        </li>
                        <li class="py-4">
                            <router-link to="/admin/coupons">Coupons</router-link>
                        </li>
                        <li class="py-4">
                            <router-link to="/admin/user">Users</router-link>
                        </li>
                        <hr />
                    </ul>
                </v-list-item>
            </v-list>
        </v-navigation-drawer>

        <!-- App bar (header) -->
        <v-app-bar app>
            <v-app-bar-nav-icon
                @click.stop="drawer = !drawer"
            ></v-app-bar-nav-icon>
            <v-toolbar-title>Admin Dashboard</v-toolbar-title>
            <v-spacer></v-spacer>
            <v-btn icon @click="logout">
                <v-icon>mdi-logout</v-icon>
            </v-btn>
        </v-app-bar>

        <!-- Main content -->
        <v-main class="custom">
            <router-view></router-view>
        </v-main>
    </v-app>
</template>

<script setup>
import { ref } from "vue";
import { useRouter } from "vue-router";
import { useAuthStore } from "../stores/auth";

const drawer = ref();
const router = useRouter();
const authStore = useAuthStore();

const logout = () => {
    authStore.removeToken();
    authStore.removeUser();
    router.replace("/login");
};
</script>

<!-- <style scoped>
.custom {
    --v-layout-left: 0px;
    --v-layout-right: 0px;
    --v-layout-top: 0px;
    --v-layout-bottom: 0px;
}
</style> -->
