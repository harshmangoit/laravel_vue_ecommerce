<template>
    <div class="v-container">
        <h1 class="text-2xl font-bold mb-4">
            My Wishlist
            <span v-if="wishlist.length != 0">({{ wishlist.length }})</span>
        </h1>

        <div v-if="wishlist.length === 0" class="text-gray-600">
            Your wishlist is empty.
        </div>

        <div v-else>
            <div
                v-for="product in products"
                :key="product.id"
                class="mb-4 p-4 border rounded"
            >
                <!-- class="m-4 relative w-full md:w-1/3 lg:w-1/4 xl:w-1/5" -->
                <div class="flex items-center justify-between">
                    <RouterLink :to="`/store/${product.url_slug}`">
                        <div class="flex items-center">
                            <img
                                :src="product.image_path"
                                alt="Product Image"
                                class="w-20 h-20 object-cover mr-6"
                            />
                            <div>
                                <h2 class="text-lg font-bold">
                                    {{ product.name }}
                                </h2>

                                <p class="text-lg font mr-4">
                                    Rs. {{ product.price }}
                                </p>
                            </div>
                        </div>
                    </RouterLink>
                    <div>
                        <v-icon
                            @click="addToCart(product)"
                            class="mr-6"
                            style="color: rgb(78, 82, 87)"
                            >mdi-cart</v-icon
                        >
                        <v-icon
                            @click="removeFromWishlist(product)"
                            class="mr-6"
                            color="red"
                            >mdi-delete</v-icon
                        >
                    </div>
                    <!-- <v-btn> -->
                    <!-- <v-icon
                        style="width: 1px; height: 60px; border-radius: 50%"
                        :class="{
                            'cart-button': true,
                            added: addedToCart,
                        }"
                        @click="toggleCart"
                        >{{
                            addedToCart ? "mdi-cart" : "mdi-cart-outline"
                        }}</v-icon
                    > -->
                    <!-- </v-btn> -->
                </div>
            </div>
        </div>
    </div>
</template>

<script setup>
import { useAuthStore } from "../stores/auth";
import { useCartStore } from "../stores/cart";
import { ref, onMounted } from "vue";
import axios from "axios";

const authStore = useAuthStore();
const cartStore = useCartStore();
const userId = authStore.isLoggedIn ? authStore.userData.id : null;

const wishlist = ref([]);
const products = ref([]);
const addedToCart = ref(false);

const removeFromWishlist = async (product) => {
    await fetchUserWishlist();
    try {
        // Find the index of the product in the wishlist array
        const productIndex = wishlist.value.findIndex(
            (item) => item.product_id === product.id
        );
        if (productIndex !== -1) {
            // If the product is in the wishlist, remove it
            await axios.delete(
                `/api/wishlists/${wishlist.value[productIndex].id}`
            );
            await fetchUserWishlist();
            // Set addedToWishlist to false after successful removal
        }
    } catch (error) {
        // Handle any errors that may occur during the request
        console.error("Error removing product from wishlist:", error);
    }
};

const alreadyInCart = (product) => {
    const x = cartStore.items?.find((item) => item.id === product.id);
    if (x?.id) {
        return true;
    } else {
        return false;
    }
};

const addToCart = (product) => {
    if (!alreadyInCart(product)) {
        cartStore.addItem(product);
    } else {
        alert(`${product.name} already in cart`);
        // addedToCart.value = true;
    }
};

// const toggleCart = () => {
//     if (addedToCart.value) {
//         removeFromCart();
//     } else {
//         addToCart();
//     }
// };

const fetchUserWishlist = async () => {
    const response = await axios.get(`/api/wishlists/${userId}`);
    wishlist.value = response.data;
    products.value = response.data.map((item) => item.product);
};
onMounted(fetchUserWishlist);
</script>

<style scoped>
.cart-button {
    color: #000; /* Default color */
}
.cart-button.added {
    color: blue; /* Color when added to wishlist */
}
</style>

<route lang="yaml">
meta:
    requiresAuth: true
</route>
