<template>
    <div class="container">
        <h3 class="text-2xl font-semibold text-center mt-4 mb-6 text-gray-800">
            Orders<span
                class="inline-block ml-2 px-2 py-1 bg-gray-200 text-gray-800 rounded"
                >{{ orders.length }}</span
            >
        </h3>

        <v-data-table
            :headers="columns"
            :items="orders"
            show-select
            :search="search"
            :items-per-page="5"
        >
            <template v-slot:item.id="{ item }">
                <span
                    ><router-link
                        :to="`/admin/order/${item.id}`"
                        class="text-blue-500"
                        >#{{ item.id }}</router-link
                    ></span
                >
            </template>
            <template v-slot:item.total_amount="{ item }">
                <span>Rs.{{ item.total_amount }}</span>
            </template>
            <template v-slot:item.created_at="{ item }">
                <span>{{ formatDate(item.created_at) }}</span>
            </template>
            <template v-slot:item.status="{ item }">
                <span
                    v-if="item.status === 'succeeded'"
                    class="inline-block px-2 py-1 text-xs font-semibold rounded bg-green-100 text-green-500"
                    ><span
                        class="inline-block w-3 h-3 mr-1 rounded-full bg-green-500"
                    ></span
                    >Paid</span
                >
                <span
                    v-else-if="item.status === 'pending'"
                    class="inline-block px-2 py-1 text-xs font-semibold rounded bg-yellow-100 text-yellow-500"
                    ><span
                        class="inline-block w-3 h-3 mr-1 rounded-full bg-yellow-500"
                    ></span
                    >Pending</span
                >
                <span
                    v-else-if="item.status === 'processing'"
                    class="inline-block px-2 py-1 text-xs font-semibold rounded bg-orange-100 text-orange-500"
                    ><span
                        class="inline-block w-3 h-3 mr-1 rounded-full bg-orange-500"
                    ></span
                    >Processing</span
                >
                <span
                    v-else-if="item.status === 'shipped'"
                    class="inline-block px-2 py-1 text-xs font-semibold rounded bg-blue-100 text-blue-500"
                    ><span
                        class="inline-block w-3 h-3 mr-1 rounded-full bg-blue-500"
                    ></span
                    >Shipped</span
                >
            </template>
            <template v-slot:item.payment_method="{ item }">
                <span v-if="item.payment_method === 'cod'"
                    >Cash on Delivery</span
                >
                <span v-else
                    ><div class="flex items-center">
                        <img
                            class="w-8 h-6 me-2"
                            src="data:image/png;base64,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"
                            alt="Image Description"
                        />
                        <span class="text-dark">•••• 4242</span>
                    </div></span
                >
            </template>
            <template v-slot:item.action="{ item }">
                <v-btn @click="redirectToOrder(item.id)" color="">
                    <v-icon>mdi-eye</v-icon>
                    View
                </v-btn>
            </template></v-data-table
        >
    </div>
</template>

<script setup>
import { ref, onMounted } from "vue";
import { useRouter } from "vue-router";
import axios from "axios";

const router = useRouter();

const columns = [
    { title: "ORDER", value: "id", sortable: true },
    {
        title: "DATE",
        value: "created_at",
        sortable: true,
    },
    {
        title: "CUSTOMER",
        value: "address.name",
        sortable: true,
    },
    // { title: "EMAIL", value: "address.email", sortable: true },
    { title: "STATUS", value: "status", sortable: true },
    { title: "PAYMENT METHOD", value: "payment_method", sortable: true },
    { title: "TOTAL", value: "total_amount", sortable: true },
    { title: "ACTIONS", value: "action", sortable: false },
];

const search = ref("");

function redirectToOrder(id) {
    router.push({ name: "admin-order-id", params: { id } });
}

function formatDate(dateTimeString) {
    const options = {
        month: "short",
        day: "numeric",
        year: "numeric",
        hour: "numeric",
        minute: "numeric",
        second: "numeric",
        hour12: true,
    };

    const date = new Date(dateTimeString);
    return date.toLocaleString("en-US", options);
}

const orders = ref([]);

const fetchOrders = async () => {
    try {
        const response = await axios.get("/api/orders");
        orders.value = response.data;
    } catch (error) {
        console.error("Error fetching product data:", error);
    }
};

onMounted(fetchOrders);
</script>

<style></style>

<route lang="yaml">
meta:
    requiresAuth: true
    layout: AdminLayout
</route>
