<template>
    <div class="bg-gray-100 min-h-screen p-8">
        <div class="max-w-3xl mx-auto bg-white p-8 rounded shadow-md">
            <!-- User Details Section -->
            <div class="mb-8">
                <h1 class="text-2xl font-semibold mb-4">My Profile</h1>
                <div class="flex items-center">
                    <img
                        src="https://i0.wp.com/www.spielanime.com/wp-content/uploads/2023/08/Jujutsu-Kaisen-Chapter-234-spoilers-How-does-Gojo-Satoru-lose-his-arm-against-Sukuna-.jpg?fit=1024%2C576&ssl=1"
                        alt="Profile"
                        class="rounded-full w-16 h-16 mr-4"
                    />
                    <div>
                        <h2 class="text-xl font-semibold">
                            {{ name }}
                        </h2>
                        <p class="text-gray-600">{{ email }}</p>
                    </div>
                </div>
            </div>

            <!-- Account Settings Section -->
            <div>
                <h1 class="text-2xl font-semibold mb-4">Account Settings</h1>
                <div>
                    <label
                        for="fullName"
                        class="block text-sm font-medium text-gray-600"
                        >Full Name</label
                    >
                    <input
                        v-model="user.name"
                        type="text"
                        id="fullName"
                        name="fullName"
                        class="mt-1 p-2 w-full border rounded"
                    />
                </div>
                <div class="mt-4">
                    <label
                        for="email"
                        class="block text-sm font-medium text-gray-600"
                        >Email Address</label
                    >
                    <input
                        v-model="user.email"
                        type="email"
                        id="email"
                        name="email"
                        class="mt-1 p-2 w-full border rounded"
                    />
                </div>
                <button
                    @click="saveChanges"
                    class="mt-8 bg-blue-500 text-white py-2 px-4 rounded hover:bg-blue-600"
                >
                    Save Changes
                </button>
            </div>
        </div>
    </div>

    <!-- Order History Section -->
    <!-- <div class="mb-8">
                <h1 class="text-2xl font-semibold mb-4">Order History</h1>
                <div v-if="user.orders.length === 0">
                    <p>No orders yet.</p>
                </div>
                <div v-else>
                    <div
                        v-for="order in user.orders"
                        :key="order.id"
                        class="mb-4"
                    >
                        <div class="flex items-center justify-between">
                            <p class="text-lg font-semibold">
                                Order #{{ order.orderNumber }}
                            </p>
                            <p class="text-gray-600">{{ order.date }}</p>
                        </div>
                        <div class="flex items-center">
                            <div
                                v-for="product in order.products"
                                :key="product.id"
                                class="flex items-center mr-4"
                            >
                                <img
                                    :src="product.image"
                                    alt="Product"
                                    class="w-12 h-12 mr-2"
                                />
                                <p>{{ product.name }}</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div> -->
</template>

<script setup>
import { ref, onMounted } from "vue";
import { useAuthStore } from "../stores/auth";
const authStore = useAuthStore();
const name = ref(authStore.getUser.name);
const email = ref(authStore.getUser.email);
const user = ref([]);
user.value = authStore.getUser;

// onMounted(fetchUserData)
</script>

<style lang="scss" scoped></style>
