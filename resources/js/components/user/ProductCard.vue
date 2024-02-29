<script setup>
import { defineProps } from "vue";

defineProps(['name', 'price', 'url_slug', 'image_path']);
</script>
<template>
    <RouterLink
        class="m-4 relative w-full md:w-1/3 lg:w-1/4 xl:w-1/5"
        :to="`/store/${url_slug}`"
    >
        <!-- <span
      v-if="badge"
      class="absolute bg-primary font-serif p-1 right-0 rounded-bl-md rounded-br-none rounded-tl-none rounded-tr-sm text-white text-xs z-10"
      >{{ badge }}</span
    > -->
        <v-card border variant="outlined" class="md:h-full">
            <div class="h-60 relative">
                <div class="-m-4 bg-white p-6 rounded-lg">
                    <div>
                        <!-- <v-img
              v-if="imageOptimized"
              :src="imageOptimized.src"
              :srcset="imageOptimized.srcset"
              :sizes="imageOptimized.sizes"
              height="250"
            /> -->
                        <!-- v-else -->
                        <v-img
                            :src="`http://127.0.0.1:8000` + image_path"
                            height="260"
                            width="300"
                        >
                        </v-img>
                    </div>
                </div>
            </div>
            <v-card-text class="mt-13">
                <div>
                    <span class="text-base">{{ name }}</span>
                    <div
                        class="flex font-medium items-center justify-between mt-4"
                    >
                        <span class="font-bold text-base">Rs.{{ price }}</span>
                        <!-- <v-chip
              v-if="shipping"
              class="dark:text-primary-400 text-primary-500 text-xs"
              size="small"
              color="primary"
              >{{ shipping }}</v-chip
            > -->
                    </div>
                </div>
            </v-card-text>
        </v-card>
    </RouterLink>
</template>
<style scoped></style>
