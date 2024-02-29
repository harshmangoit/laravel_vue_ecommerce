<script setup>
import ProductCard from "../components/user/ProductCard.vue";
// import useCategory from "../composables/category";
import axios from "axios";
import { ref, watch, onMounted } from "vue";
import { useRoute } from "vue-router";
import CategorySection from "../components/user/CategorySection.vue";
const route = useRoute();
// const capitalize = (s) => (s && s[0].toUpperCase() + s.slice(1)) || "";

// const { category } = route.params;
// const { fetchCategory } = await useProducts()
// const products = fetchCategory(category?.toString())
const products = ref([]);

const fetchProductsByCategory = async () => {
    const response = await axios.get(
        `http://127.0.0.1:8000/api/category/${route.params.category}`
    );
    products.value = response.data.products;
    // console.log(products.value)
};

onMounted(fetchProductsByCategory);

watch(
    () => route.params.category,
    () => {
        fetchProductsByCategory();
    }
);
// const { fetchProductsByCategory } = await useCategory(category);
// const products = fetchProductsByCategory();

// const badges = [
//   ...new Set(
//     products
//       .filter((product) => product.badge)
//       .map((product) => product.badge),
//   ),
// ]

// const filteredBadges = useState('filteredBadges', () => {
//   return new Set()
// })
// const filteredBadges = useState(category?.toString() || 'all', () => {
//   return new Set()
// })

// const toggleFilter = (badge) => {
//   if (filteredBadges.value.has(badge)) {
//     filteredBadges.value.delete(badge)
//   } else {
//     filteredBadges.value.add(badge)
//   }
// }

// const filteredProducts = computed(() => {
//   if (filteredBadges.value.size) {
//     return products.filter((product) =>
//       filteredBadges.value.has(product.badge),
//     )
//   } else {
//     return products
//   }
// })
</script>
<template>
    <CategorySection/>
    <div class="pb-16">
        <v-container class="py-8">
            <!-- <section
        v-if="badges.length"
        class="flex items-center justify-center m-4"
      >
        <span class="font-bold text-sm">Filter Badges</span>
        <div class="ml-4 mt-2">
          <v-btn
            v-for="(badge, index) in badges"
            :key="index"
            :text="badge"
            :variant="filteredBadges.has(badge) ? 'tonal' : 'outlined'"
            class="mr-2"
            @click="toggleFilter(badge)"
          ></v-btn>
        </div>
      </section> -->
            <section
                data-pg-name="Products"
                class="flex flex-wrap justify-center"
            >  
            <!-- <div v-for="product in products">
                {{ product.price }}
            </div>  -->
                <ProductCard
                    v-for="product in products"
                    :key="product.id"
                    v-bind="product"
                />
            </section>
        </v-container>
    </div>
</template>
<style scoped></style>
