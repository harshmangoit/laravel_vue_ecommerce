<template>
    <div class="container">
        <h3 class="text-2xl font-semibold text-center mt-4 mb-6 text-gray-800">
            Product List
        </h3>
        <div class="flex justify-between items-center mb-4">
            <router-link :to="{ name: 'admin-product-create' }">
                <v-btn color="primary">Add Product</v-btn></router-link
            >
            <div class="flex items-center">
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
            </div>
        </div>
        <v-data-table :headers="columns" :items="products" :items-per-page="5">
            <template v-slot:item.image_path="{ item }">
                <v-img
                    :src="item.image_path"
                    width="100"
                    height="100"
                    class="img img-responsive"
                ></v-img>
            </template>

            <template v-slot:item.action="{ item }">
                <v-btn @click="handleEdit(item.url_slug)" color="primary"
                    > <v-icon>mdi-pencil</v-icon></v-btn
                >
                <v-btn @click="deleteProduct(item.url_slug)" color="error"
                    > <v-icon>mdi-delete</v-icon></v-btn
                >
            </template></v-data-table
        >
    </div>
</template>

<script setup>
import DataTable from "datatables.net-vue3";
import DataTablesCore from "datatables.net";
import { ref, onMounted } from "vue";
import { useRouter } from "vue-router";
import axios from "axios";

DataTable.use(DataTablesCore);

const router = useRouter();

const columns = [
    { title: "Image", value: "image_path", sortable: true },
    { title: "Name", value: "name", sortable: true },
    {
        title: "Description",
        value: "description",
        width: "600",
        sortable: true,
    },
    { title: "Price", value: "price", sortable: true },
    { title: "Action", value: "action", sortable: false },
];

const selectedFormat = ref("json");

const convertToCSV = (data) => {
    if (!data || data.length === 0) {
        return ""; // Return an empty string or handle appropriately for your use case
    }

    const header = Object.keys(data[0]).join(",");
    const rows = data.map((product) => Object.values(product).join(","));
    return `${header}\n${rows.join("\n")}`;
};

const downloadFile = (data, fileName, fileType) => {
    const blob = new Blob([data], { type: fileType });
    const a = document.createElement("a");
    a.href = URL.createObjectURL(blob);
    a.download = fileName;
    a.click();
};

function exportData() {
    if (selectedFormat.value === "csv") {
        const productsCSV = convertToCSV(products.value);
        downloadFile(productsCSV, "products.csv", "text/csv");
    } else if (selectedFormat.value === "json") {
        const productsJSON = JSON.stringify(products.value, null, 2);
        downloadFile(productsJSON, "products.json", "application/json");
    }
}

const products = ref([]);

const fetchProducts = async () => {
    try {
        const response = await axios.get("/api/products");
        products.value = response.data;
    } catch (error) {
        console.error("Error fetching product data:", error);
    }
};

const handleEdit = async (urlSlug) => {
    router.push({
        name: "admin-product-slug",
        params: { slug: urlSlug },
    });
};

const deleteProduct = async (slug) => {
    axios
        .delete(`/api/products/${slug}`)
        .then((response) => {
            console.log("Product has been deleted");
            fetchProducts();
        })
        .catch((error) => {
            console.error(error);
        });
};

onMounted(fetchProducts);
</script>

<style></style>

<route lang="yaml">
meta:
    requiresAuth: true
    layout: AdminLayout
</route>
