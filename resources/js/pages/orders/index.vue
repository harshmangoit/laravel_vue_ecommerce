<template>
    <div class="v-container">
        <h1 class="text-2xl text-center font-semibold mb-6">My Orders</h1>
        <!-- <span v-for="order in orderDetailByProduct">{{ order.id }} </span> -->
        <div
            v-for="productDetail in orderDetailByProduct"
            :key="productDetail.product.id"
        >
            <div class="border p-4 mb-4">
                <div class="flex items-center mb-4">
                    <div class="mr-10 pr-5">
                        <router-link :to="`/orders/${productDetail.orderId}`">
                            <div class="h-20 w-20">
                                <img
                                    loading="lazy"
                                    class="h-full w-full object-cover"
                                    :src="productDetail.product.image_path"
                                    alt="Product Image"
                                />
                            </div>
                        </router-link>
                    </div>
                    <div class="flex-1">
                        <div class="flex justify-between items-center">
                            <div class="font-semibold text-lg">
                                <router-link
                                    :to="`/orders/${productDetail.orderId}`"
                                >
                                    {{ productDetail.product.name }}
                                </router-link>
                            </div>
                            <div class="flex items-center">
                                <div class="text-lg font-bold text-gray-700">
                                    ₹{{ productDetail.product.price }}
                                </div>
                                <div class="flex ml-40 pl-40">
                                    <div class="flex-1">
                                        {{
                                            formatDate(productDetail.orderDate)
                                        }}
                                    </div>
                                    <div class="ml-10">
                                        <button
                                            class="flex"
                                            @click="
                                                viewOrder(
                                                    productDetail.product
                                                        .url_slug
                                                )
                                            "
                                        >
                                            <img
                                                src="data:image/svg+xml;base64,PHN2ZyB3aWR0aD0nMTYnIGhlaWdodD0nMTknIHZlcnNpb249IjEuMSIgeG1sbnM9Imh0dHA6Ly93d3cudzMub3JnLzIwMDAvc3ZnIiB2aWV3Qm94PSIwIDAgMTggMTgiPgoJPGcgZmlsbD0nbm9uZSc+CgkJPHBvbHlnb24gaWQ9IlNoYXBlIiBmaWxsPSIjMjg3NEYxIiBwb2ludHM9IjkgMTIuMDYyNSAxMy42Mzc1IDE1LjQzNzUgMTEuODYyNSA5Ljk4NzUgMTYuNSA2LjY4NzUgMTAuODEyNSA2LjY4NzUgOSAxLjA2MjUgNy4xODc1IDYuNjg3NSAxLjUgNi42ODc1IDYuMTM3NSA5Ljk4NzUgNC4zNjI1IDE1LjQzNzUiIC8+CgkJPHBvbHlnb24gaWQ9IlNoYXBlIiBwb2ludHM9IjAgMCAxOCAwIDE4IDE4IDAgMTgiIC8+Cgk8L2c+Cjwvc3ZnPg=="
                                                alt="Review Product"
                                                class="w-6 h-6"
                                            />
                                            <span class="text-blue-500"
                                                >Rate &amp; Review Product</span
                                            >
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script setup>
import { ref, onMounted } from "vue";
import { useRouter } from "vue-router";
import { useAuthStore } from "../../stores/auth";
import axios from "axios";

const router = useRouter();
const authStore = useAuthStore();
const userId = authStore.userData.id;

const orders = ref([]);
const orderItems = ref([]);
const products = ref([]);
const orderDetailByProduct = ref([]);

function viewOrder(slug) {
    router.push({ name: "review-slug", params: { slug: slug } });
}

function formatDate(dateTimeString) {
    const options = {
        month: "short",
        day: "numeric",
        year: "numeric",
        // hour: "numeric",
        // minute: "numeric",
        // second: "numeric",
        // hour12: true,
    };

    const date = new Date(dateTimeString);
    return date.toLocaleString("en-US", options);
}

const fetchOrders = async () => {
    try {
        const response = await axios.get(`/api/auth/orders/${userId}`);
        orders.value = response.data;
        orderItems.value = orders.value.flatMap((order) => order.order_items);
        products.value = orderItems.value.flatMap(
            (orderItem) => orderItem.product
        );
        orderDetailByProduct.value = orderItems.value.map((orderItem) => {
            return {
                orderId: orderItem.order.id,
                orderDate: orderItem.order.created_at,
                product: orderItem.product,
            };
        });
        // console.log(orderDetailByProduct.value);
    } catch (error) {
        console.error("Error fetching product data:", error);
    }
};

onMounted(fetchOrders);
</script>

<style lang="scss" scoped></style>

<route lang="yaml">
meta:
    requiresAuth: true
</route>
