<script setup>
import ProductCard from "../../components/user/ProductCard.vue";
import { ref, onMounted } from "vue";
import axios from "axios";
import bus from "../../plugins/eventBus";

const searchText = ref("");
const products = ref([]);
const featuredProducts = ref([]);
// const { category } = route.params;

bus.on("search", (text) => {
    searchText.value = text;
    searchProducts();
});

const fetchProducts = async () => {
    const response = await axios.get("/api/products");
    products.value = response.data;
    featuredProducts.value = response.data;
};

const searchProducts = () => {
    if (searchText.value.trim() === "") {
        // If search text is empty, show all products
        products.value = featuredProducts.value;
    } else {
        // Filter products based on the search text
        products.value = featuredProducts.value.filter(
            (product) =>
                product.name
                    .toLowerCase()
                    .includes(searchText.value.toLowerCase()) ||
                product.price
                    .toString()
                    .includes(searchText.value.toLowerCase()) ||
                product.description
                    .toLowerCase()
                    .includes(searchText.value.toLowerCase())
        );
    }
};

onMounted(fetchProducts);
</script>
<template>
    <div>
        <div class="-tracking-wide flex font-bold justify-center text-3xl">
            <span class="text-center text-primary">Latest Products 🔥</span>
        </div>
        <section data-pg-name="Products" class="flex flex-wrap justify-center">
            <ProductCard
                v-for="(product, index) in products"
                :key="index"
                v-bind="product"
            />
        </section>
    </div>
</template>

<style scoped>
/* Your existing styles */
.flex {
    display: flex;
}

.flex-wrap {
    flex-wrap: wrap;
}

/* Additional styles for responsiveness */
@media screen and (max-width: 768px) {
    .flex-wrap {
        flex-wrap: nowrap; /* Prevent wrapping on smaller screens */
        overflow-x: auto; /* Add horizontal scrollbar for overflow */
    }
}
</style>
