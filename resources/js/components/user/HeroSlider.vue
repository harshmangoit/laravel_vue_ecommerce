<template>
    <div>
        <v-carousel :cycle="true" :interval="3000" height="350" hide-delimiters  show-arrows="hover">
            <v-carousel-item v-for="(product, i) in featuredProducts" :key="i">
                <router-link :to="`/store/${product.url_slug}`">
                    <v-img :src="product.image_path" aspect-ratio="2.7">
                        <!-- <v-row
                            class="fill-height ma-0 pa-4"
                            align="end"
                            justify="end"
                            >
                            <v-col>
                                <v-card class="text-center transparent">
                                    <v-card-title
                                        class="headline font-weight-bold mb-2"
                                    >
                                        {{ product.name }}
                                    </v-card-title>
                                    <v-card-subtitle class="body-2">
                                        Rs.{{ product.price }}
                                    </v-card-subtitle>
                                </v-card>
                            </v-col>
                        </v-row> -->
                    </v-img>
                </router-link>
            </v-carousel-item>
        </v-carousel>
    </div>
</template>

<script setup>
import { ref, onMounted } from "vue";
import axios from "axios";
const items = ref([]);
const products = ref([]);
const featuredProducts = ref([]);

const fetchProducts = async () => {
    const response = await axios.get("/api/products");
    products.value = response.data;
    // console.log(products.value);

    // Filter products with is_featured: 1
    featuredProducts.value = products.value.filter(
        (product) => product.is_featured === 1
    );

    // Extract image paths from featured products
    items.value = featuredProducts.value.map((product) => product.image_path);
};

onMounted(fetchProducts);
</script>

<style lang="scss" scoped></style>
