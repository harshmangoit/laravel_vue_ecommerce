<script setup>
import { ref } from "vue";
const categories = ref([
    { id: 1, name: "Electronics", slug: "electronics" },
    { id: 2, name: "Computers & Accessories", slug: "computer-accessory" },
    { id: 3, name: "Cameras & Photography", slug: "camera-photography" },
    { id: 4, name: "Watches", slug: "watch" },
    { id: 5, name: "Sports & Fitness", slug: "sport-fitness" },
]);
</script>

<template>
    <div>
        <v-container>
            <!-- <div class="py-2"> -->
            <!-- <div class="mx-auto"> -->
            <div class="flex justify-center space-x-6 mb-2">
                <router-link
                    v-for="category in categories"
                    :key="category.id"
                    :to="`/${category.slug}`"
                    class="px-4 py-2 dark:bg-neutral-300 rounded-full text-sm font-medium text-white-700 dark:text-black-300 hover:bg-gray-100 dark:hover:bg-neutral-500 cursor-pointer transition-all"
                >
                    {{ category.name }}
                </router-link>
            </div>
            <!-- </div> -->
            <!-- </div> -->
        </v-container>
        <!-- <div class="py-2">
        <div class="mx-auto">
            <span
                v-for="category in categories"
                :key="category.id"
                class="_2I9KP_ text-gray-700 hover:text-gray-900 cursor-pointer"
            >
                {{ category.name }}
            </span>
            
        </div>
    </div> -->
    </div>
</template>
