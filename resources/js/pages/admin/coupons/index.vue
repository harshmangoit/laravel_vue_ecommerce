<template>
    <div class="container">
        <h3 class="text-2xl font-semibold text-center mt-4 mb-6 text-gray-800">
            Coupons
            <!-- <span
                class="inline-block ml-2 px-2 py-1 bg-gray-200 text-gray-800 rounded"
                >{{ invoices.length }}</span
            > -->
        </h3>
        <div class="flex items-end mb-4 ml-2">
            <router-link :to="{ name: 'admin-coupons-create' }">
                <v-btn color="primary">Add Coupon</v-btn></router-link
            >
            <!-- <div class="flex items-center">
                <div>
                    <label for="exportFormat" class="mr-1"
                        >Export Format:</label
                    >
                    <select
                        v-model="selectedFormat"
                        id="exportFormat"
                        class="p-1"
                    >
                        <option value="csv">CSV</option>
                        <option value="json">JSON</option>
                    </select>
                </div>
                <v-btn color="secondary" @click="exportData" class="ml-2">
                    Export
                </v-btn>
            </div> -->
        </div>
        <v-data-table :headers="columns" :items="coupons" :items-per-page="5">
            <template v-slot:item.code="{ item }">
                <span class="text-blue-500"># {{ item.code }}</span>
            </template>
            <!-- <template v-slot:item.created_at="{ item }">
                <span>{{ formatDate(item.created_at) }}</span>
            </template> -->
            <!-- <template v-slot:item.total_amount="{ item }">
                <span>Rs.{{ item.total_amount }}</span>
            </template> -->
            <template v-slot:item.action="{ item }">
                <v-btn @click="editItem(item.code)"  width="30" height="30" color="primary" icon>
                    <v-icon>mdi-pencil</v-icon>
                </v-btn>&nbsp;
                <v-btn @click="deleteItem(item.id)" width="30" height="30" color="error" icon>
                    <v-icon>mdi-delete</v-icon>
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

const coupons = ref([]);
const isLoading = ref(true);

const columns = [
    { title: "CODE", value: "code", sortable: true },
    { title: "DISCOUNT TYPE", value: "discount_type", sortable: true },
    {
        title: "DISCOUNT AMOUNT",
        value: "discount_amount",
        sortable: true,
    },
    { title: "START DATE", value: "start_date", sortable: true },
    { title: "END DATE", value: "end_date", sortable: true },
    { title: "MAX USES", value: "max_uses", sortable: true },
    { title: "CURRENT USES", value: "current_uses", sortable: true },
    { title: "ACTIONS", value: "action", sortable: false },
];

function editItem(code) {
    router.push({ name: "admin-coupons-code", params: { code } });
}

const deleteItem = async (id) => {
    axios
        .delete(`/api/coupons/${id}`)
        .then((response) => {
            // console.log("Coupon has been deleted");
            fetchCoupons();
        })
        .catch((error) => {
            console.error(error);
        });
};

const fetchCoupons = async () => {
    isLoading.value = true;
    try {
        const response = await axios.get("/api/coupons");
        coupons.value = response.data;
        isLoading.value = false;
    } catch (error) {
        console.error("Error fetching coupons:", error);
    }
};
onMounted(fetchCoupons);
</script>

<style lang="scss" scoped></style>

<route lang="yaml">
meta:
    requiresAuth: true
    layout: AdminLayout
</route>
