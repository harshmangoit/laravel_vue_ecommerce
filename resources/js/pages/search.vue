<template>
    <div>
        <!-- <div class="container mx-auto pt-8"> -->
        <v-container>
            <div v-if="filteredProducts.length" class="text-gray-600">
                Showing {{ filteredProducts.length }} results for "<span
                    class="font-semibold"
                    >{{ searchText }}</span
                >"
            </div>
            <section
                v-if="filteredProducts.length"
                data-pg-name="Products"
                class="flex flex-wrap justify-center"
            >
                <ProductCard
                    v-for="(product, index) in filteredProducts"
                    :key="index"
                    v-bind="product"
                />
            </section>
            <div v-else class="flex flex-col items-center mt-10">
                <img
                    src="data:image/png;base64,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"
                    alt="No Search Results Image"
                    class="w-48 h-48"
                />
                <div class="text-xl font-bold mt-4">
                    Sorry, no results found!
                </div>
                <div class="text-gray-600 mt-2">
                    Please check the spelling or try searching for something
                    else
                </div>
            </div>
        </v-container>
        <!-- </div> -->
    </div>
</template>

<script setup>
import ProductCard from "../components/user/ProductCard.vue";
import { ref, onMounted, watch } from "vue";
import { useRoute } from "vue-router";
import axios from "axios";
const route = useRoute();

const searchText = ref("");
const products = ref([]);
const filteredProducts = ref([]);

const searchProducts = () => {
    filteredProducts.value = products.value.filter((product) => {
        // Convert category names to lowercase for case-insensitive matching
        const categoryNames = product.category.map((cat) =>
            cat.name.toLowerCase()
        );

        return (
            product.name
                .toLowerCase()
                .includes(searchText.value.toLowerCase()) ||
            product.price.toString().includes(searchText.value.toLowerCase()) ||
            product.description
                .toLowerCase()
                .includes(searchText.value.toLowerCase()) ||
            categoryNames.some((catName) =>
                catName.includes(searchText.value.toLowerCase())
            )
        );
    });
    // console.log(products.value[0].category[0].name);
};

// Watch the 'q' query parameter
watch(
    () => route.query.q,
    (newQuery, oldQuery) => {
        searchText.value = newQuery;
        searchProducts();
    }
);

onMounted(async () => {
    const response = await axios.get("/api/products");
    products.value = response.data;
    searchText.value = route.query.q;
    searchProducts();
});
</script>

<style lang="scss" scoped></style>
