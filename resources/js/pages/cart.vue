<script setup>
import SharedPrice from "../components/user/shared/SharedPrice.vue";
import CheckoutCartItem from "../components/user/checkout/CheckoutCartItem.vue";
import CheckoutPromotionCode from "../components/user/checkout/CheckoutPromotionCode.vue";
import { computed } from "vue";
import { useCartStore } from "../stores/cart";
const cartStore = useCartStore();
const items = computed(() => useCartStore().items);
const totalItems = computed(() => useCartStore().totalItems);
const totalPrice = computed(() => useCartStore().totalPrice);
const discountPrice = computed(() => useCartStore().discountPrice);
const couponDiscount = computed(() => useCartStore().couponDiscount);
</script>

<template>
    <div v-if="totalItems" class="m-10">
        <h1 class="mb-3 text-2xl font-medium text-secondary-900">
            Shopping Cart ({{ totalItems }} Items)
        </h1>

        <div class="my-10 md:grid md:grid-cols-3 md:gap-8">
            <ul
                role="list"
                class="divide-y pl-0 divide-secondary-200 md:col-span-2 border-t"
            >
                <li v-for="item in items" :key="item.id" class="flex py-6">
                    <CheckoutCartItem :cart-item="item" />
                </li>
            </ul>

            <aside
                class="md:col-span-1 pb-4 px-4 bg-secondary-50 rounded dark:bg-secondary-800"
            >
                <h2
                    class="text-xl font-medium text-secondary-900"
                    style="font-size: 31px; font-weight: 700"
                >
                    Order Summary
                </h2>

                <div
                    class="flex py-2 mt-4 justify-between text-sm text-secondary-500"
                >
                    <p>Subtotal</p>
                    <SharedPrice
                        :value="totalPrice"
                        class="text-secondary-900 font-medium"
                        data-testid="cart-subtotal"
                    />
                </div>

                <div
                    class="flex py-4 border-b justify-between text-sm text-secondary-500"
                >
                    <p>Shipping Estimate</p>
                    Free
                    <!-- <SharedPrice
                        :value="50"
                        class="text-secondary-900 font-medium"
                        data-testid="cart-subtotal"
                    /> -->
                </div>

                <div
                v-if="couponDiscount"
                    class="flex py-4 border-b justify-between text-sm text-secondary-500"
                >
                    <p>Coupon Discount: ({{ cartStore.coupon.code }})</p>
                    
                    <div>
                        Rs. {{ couponDiscount }}
                    </div>
                    <!-- <div v-else>
                        Rs. 0
                    </div> -->
                    <!-- <SharedPrice
                        :value="50"
                        class="text-secondary-900 font-medium"
                        data-testid="cart-subtotal"
                    /> -->
                </div>

                <div
                    class="flex py-4 mb-8 justify-between text-secondary-900 font-medium"
                >
                    <p>Order Total:</p>
                    <SharedPrice
                        :value="discountPrice"
                        data-testid="cart-subtotal"
                    />
                </div>

                <div
                    class="pb-2 mb-6 justify-between text-secondary-900 font-medium"
                >
                    <CheckoutPromotionCode />
                </div>

                <RouterLink
                    class="flex items-center justify-center rounded-md px-6 py-3 text-base font-medium text-white shadow-sm bg-primary hover:bg-dark"
                    data-testid="cart-checkout-link"
                    to="/checkout"
                >
                    Checkout
                </RouterLink>
            </aside>
        </div>
    </div>
    <h1 v-else class="m-10 text-center text-2xl font-medium text-secondary-900">
        Your cart is empty!
    </h1>
</template>
