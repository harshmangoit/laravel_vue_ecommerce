<script setup lang="ts">
import { useNav } from "../../../composables/nav";
import { useAuthStore } from "../../../stores/auth";
const { navlinksSecondary } = useNav();

const dropdownItems = navlinksSecondary.value.map((navlink) => ({
    slot: navlink.link.replace(/^\//, "").replaceAll("/", "-"),
    label: navlink.text,
    icon: navlink.icon,
    to: navlink.link,
    activeClass: "text-primary",
}));

const authStore = useAuthStore();
</script>
<template>
    <v-list-item
        v-if="authStore.isLoggedIn"
        v-for="(item, index) in dropdownItems"
        :key="index"
        :value="index"
        :to="item.to"
        :title="item.label"
        :prepend-icon="item.icon"
    >
    </v-list-item>
</template>
<style scoped></style>
