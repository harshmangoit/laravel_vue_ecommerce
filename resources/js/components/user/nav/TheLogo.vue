<script setup lang="ts">
import site from "../../../composables/site";
const { name, logo } = site;
</script>
<template>
    <RouterLink
        data-pg-name="Logo"
        class="flex items-center sm:flex-row"
        to="/"
    >
        <v-icon class="text-4xl" :icon="logo"></v-icon>
        <img
            height="100"
            width="90"
            src="../../../images/e-commerce-logo.png"
            alt=""
        />
        <!-- <h5 class="font-extrabold font-serif ml-2 mt-4 text-2xl">{{ $t(name) }}</h5> -->
    </RouterLink>
</template>
<style scoped></style>
