<template>
    <div>
        <CategorySection/>
        <HeroSlider />
        <div class="container mx-auto pt-8">
            <!-- <LaunchSale class="mt-8" /> -->
            <HotDeals class="mt-8" />
        </div>
    </div>
</template>

<script setup>
import HeroSlider from "../components/user/HeroSlider.vue";
import CategorySection from "../components/user/CategorySection.vue";
import LaunchSale from "../components/user/LaunchSale.vue";
import HotDeals from "../components/user/HotDeals.vue";
</script>

<style scoped></style>
