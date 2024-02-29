<template>
    <div class="container mt-5">
        <div class="card">
            <div class="card-header">
                <h3 class="text-2xl font-semibold text-center mt-4 mb-6 text-gray-800">
                    Category List
                </h3>
                    <router-link
                        to="/admin/category/create"
                        class="btn btn-primary float-end"
                    >
                        Create Category
                    </router-link>
                
            </div>
            <div class="card-body">
                <div v-if="isLoading">
                    <!-- <Loading title="Loading" /> -->
                </div>
                <div v-else>
                    <DataTable
                        :data="categories"
                        class="display"
                        :columns="columns"
                    />
                </div>
            </div>
        </div>
    </div>
</template>

<script setup>
import DataTable from "datatables.net-vue3";
import DataTablesCore from "datatables.net";
import { ref, onMounted } from "vue";
import { useRouter } from "vue-router";
import axios from "axios";

DataTable.use(DataTablesCore);

const categories = ref([]);
const isLoading = ref(true);
const router = useRouter();

const columns = [
    { data: "position", title: "Position" },
    { data: "name", title: "Name" },
    {
        data: "status",
        title: "Status",
        render: function (data, type, row) {
            if (data == 1) return `Active`;
            else return `Inactive`;
        },
    },
    { data: "slug", title: "Slug" },
    {
        data: null,
        title: "Actions",
        render: function (data, type, row) {
            window.handleEdit = (slug) => {
                router.push({
                    name: "admin-category-slug",
                    params: { slug },
                });
            };
            window.handleDelete = deleteCategory;

            return `<v-btn class="btn btn-primary" onclick="handleEdit('${row.slug}')">Edit</v-btn>
                      <v-btn class="btn btn-danger" onclick="handleDelete(${row.id})">Delete</v-btn>`;
        },
    },
];

const fetchCategories = async () => {
    isLoading.value = true;
    try {
        const response = await axios.get("/api/category");
        categories.value = response.data;
        isLoading.value = false;
    } catch (error) {
        console.error("Error fetching category data:", error);
    }
};

const deleteCategory = async (id) => {
    alert("Are you sure you want to delete this category!");
    axios
        .delete(`/category/${id}`)
        .then((response) => {
            console.log("Category has been deleted");
            fetchCategories();
        })
        .catch((error) => {
            console.error(error);
        });
};

onMounted(fetchCategories);
</script>

<route lang="yaml">
meta:
    requiresAuth: true
    layout: AdminLayout
</route>
