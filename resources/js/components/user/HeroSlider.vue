<template>
    <v-container>
        <!-- :cycle="true" :interval="2000"   -->
        <v-carousel hide-delimiters show-arrows="hover" v-model="carouselIndex">
            <v-carousel-item-group v-if="featuredProducts.length > 0">
              <v-carousel-item v-for="(product, i) in chunkProducts(featuredProducts, 4)" :key="i">
                <v-row>
                  <v-col v-for="(product, j) in product" :key="j">
                    <router-link :to="`/store/${product.url_slug}`">
                      <v-card  border variant="outlined" style="box-shadow: 5px 5px 10px rgba(0, 0, 0, 0.3)">
                        <div class="h-64 relative">
                          <v-img :src="product.image_path" height="250"></v-img>
                        </div>
                        <v-card-text class="mt-2">
                          <span style="font-size: 25px">{{ product.name }}</span>
                          <div class="flex font-medium items-center justify-between mt-3">
                            <br />
                            <br />
                            <span style="font-size: 18px; font-weight: 800;">Rs.{{ product.price }}</span>
                          </div>
                        </v-card-text>
                      </v-card>
                    </router-link>
                  </v-col>
                </v-row>
              </v-carousel-item>
            </v-carousel-item-group>
          </v-carousel>
    </v-container>
</template>

<script setup>
import { ref, onMounted } from "vue";
import axios from "axios";
const items = ref([]);
const products = ref([]);
const featuredProducts = ref([]);
const carouselIndex = ref(0)

function chunkProducts(array, size) {
    return Array.from({ length: Math.ceil(array.length / size) }, (v, i) =>
        array.slice(i * size, i * size + size)
    );
}

const fetchProducts = async () => {
    const response = await axios.get("/api/products");
    products.value = response.data;

    featuredProducts.value = products.value.filter(
        (product) => product.is_featured === 1
    );

    items.value = featuredProducts.value.map((product) => product.image_path);
};

onMounted(fetchProducts);
</script>

<style lang="scss" scoped>

.yellow-text {
    font-size: 60px;
    color: orange;
    text-align: center;
    margin: 0;
}

.v-card {
  width: 250px;
  margin: 0 auto;
}

.v-img {
    object-fit: cover;
    border-radius: 8px;
}

.products-container {
    display: flex;
    flex-wrap: wrap;
}

.v-col {
    flex: 0 0 calc(25.00% - 10px);
    margin-bottom: 20px;
}</style>
