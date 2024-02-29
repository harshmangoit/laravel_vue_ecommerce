<script setup>
import { useMobileMenu } from "../../../composables/mobile-menu";
const { isMobileMenuOpen, openMobileMenu } = useMobileMenu();
</script>
<template>
    <VIcon
        :icon="
            isMobileMenuOpen
                ? 'i-material-symbols-close'
                : 'i-material-symbols-menu'
        "
        @click="openMobileMenu"
    >
        <span class="sr-only">Open main menu</span>
        <v-icon
            :icon="
                isMobileMenuOpen
                    ? 'i-material-symbols-close'
                    : 'i-material-symbols-menu'
            "
            size="20px"
        ></v-icon>
    </VIcon>
</template>
<style scoped></style>
