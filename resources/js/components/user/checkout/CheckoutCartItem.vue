<script setup>
import SharedPrice from "../shared/SharedPrice.vue";
import { useCartStore } from "../../../stores/cart";
import { ref, defineProps } from "vue";

const { cartItem } = defineProps(["cartItem"]);
const isLoading = ref(false);

const removeCartItem = async (index) => {
    useCartStore().removeItem(index);
};
const updateQuantity = (id) => {
    // Assuming you have the cartItems array containing the cartItem objects
    const cartItems = JSON.parse(localStorage.getItem("cartItems")) || [];

    // Find the index of the cartItem with id 5 in the cartItems array
    const index = cartItems.findIndex((item) => item.id === id);

    // Update the quantity in the cartItem object
    if (index !== -1) {
        cartItems[index].quantity = cartItem.quantity;

        // Save the updated cartItems array to localStorage
        localStorage.setItem("cartItems", JSON.stringify(cartItems));
    }
};
</script>

<template>
    <div class="container">
        <table class="w-full">
            <tr>
                <!-- Product Image -->
                <td
                    class="h-24 w-24 flex-shrink-0 overflow-hidden rounded-md border border-secondary-200"
                >
                    <router-link :to="`/store/${cartItem.url_slug}`">
                        <img
                            :src="`http://127.0.0.1:8000` + cartItem.image_path"
                            height="100"
                            width="100"
                            alt="Product Image"
                            class="h-full w-full object-cover object-center"
                            data-testid="cart-product-image"
                        />
                    </router-link>
                </td>

                <!-- Product Name -->
                <td>
                    <router-link :to="`/store/${cartItem.url_slug}`">
                        <h3
                            class="text-base ml-8"
                            data-testid="cart-product-name"
                        >
                            {{ cartItem.name }}
                        </h3>
                    </router-link>
                </td>
                <!-- Product Price -->
                <td height="10" width="250">
                    <div>
                        <SharedPrice
                            v-if="cartItem.price"
                            :value="cartItem.price"
                            data-testid="cart-product-price"
                        />
                    </div>
                </td>

                <!-- Quantity -->
                <td height="10" width="300">
                    <div>
                        <span>Qty. </span>
                        <input
                            v-model="cartItem.quantity"
                            @input="updateQuantity(cartItem.id)"
                            type="number"
                            :min="1"
                            :max="50"
                            :step="1"
                            data-testid="cart-product-qty-select"
                            name="quantity"
                            aria-label="Cart item quantity"
                            class="w-18 mt-1 inline-block py-2 px-3 border border-secondary-300 bg-white rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm"
                        />
                    </div>
                </td>

                <!-- Remove Button -->
                <td height="2" width="2">
                    <button
                        type="button"

                        class="text-medium rounded p-1 text-white bg-blue-500"
                        :class="{ 'text-secondary-500': isLoading }"
                        data-testid="product-remove-button"
                        @click="removeCartItem(cartItem.id)"
                    >
                        <v-icon height="8" width="8">mdi-delete</v-icon>
                    </button>
                </td>
            </tr>
        </table>
    </div>

    <!-- <div class="container">
        <div
            class="mr-4 h-24 w-24 flex-shrink-0 overflow-hidden rounded-md border border-secondary-200"
        >
            <router-link :to="`/store/${cartItem.urlSlug}`">
                <img
                    :src="`http://127.0.0.1:8000` + cartItem.image_path"
                    height="100"
                    width="100"
                    alt="Product Image"
                    class="h-full w-full object-cover object-center"
                    data-testid="cart-product-image"
                />
            </router-link>
        </div>

        <div class="flex flex-1 flex-col">
            <div>
                <div
                    class="flex flex-col lg:flex-row justify-between text-base font-medium text-secondary-900"
                >
                    <router-link :to="`/store/${cartItem.urlSlug}`">
                        <h3 class="text-base" data-testid="cart-product-name">
                            {{ cartItem.name }}
                        </h3>
                    </router-link>
                    <SharedPrice
                        v-if="cartItem.price"
                        :value="cartItem.price"
                        data-testid="cart-product-price"
                    />
                </div>
            </div>
            <div class="flex flex-1 items-end justify-between text-sm">
                <div>
                    <span>Qty. </span>
                    <input
                        v-model="cartItem.quantity"
                        @input="updateQuantity(cartItem.id)"
                        type="number"
                        :min="1"
                        :max="50"
                        :step="1"
                        data-testid="cart-product-qty-select"
                        name="quantity"
                        aria-label="Cart item quantity"
                        class="w-18 mt-1 inline-block py-2 px-3 border border-secondary-300 bg-white rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm"
                    />
                </div>
                <div class="flex">
                    <button
                        type="button"
                        class="text-medium rounded p-1 text-white bg-blue-500"
                        :class="{ 'text-secondary-500': isLoading }"
                        data-testid="product-remove-button"
                        @click="removeCartItem(cartItem.id)"
                    >
                        <v-icon>mdi-close</v-icon>
                    </button>
                </div>
            </div>
        </div>
    </div> -->
</template>
