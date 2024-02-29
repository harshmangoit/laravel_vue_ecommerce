<template>
    <VContainer class="mt-2">
        <VCard>
            <VRow align="center">
                <VCol>
                    <VCardTitle class="headline"> Add Product </VCardTitle>
                    <input
                        id="fileInput"
                        type="file"
                        ref="fileInput"
                        style="display: none"
                        @change="importProducts"
                    />
                    <v-btn
                        @click="triggerFileInput"
                        color="primary"
                        class="ml-4"
                        >Import</v-btn
                    >
                    <!-- <VFileInput
                        v-model="importFile"
                        label="Choose File"
                        accept=".csv, .json"
                        @change="handleFileUpload"
                    />
                    <VBtn @click="importData">Import</VBtn> -->
                </VCol>

                <VCol class="text-end">
                    <RouterLink to="/admin/product">
                        <VBtn color="secondary"> Back </VBtn>
                    </RouterLink>
                </VCol>
            </VRow>
            <VCardText>
                <VRow>
                    <VCol>
                        <VForm
                            enctype="multipart/form-data"
                            @submit.prevent="createProduct"
                        >
                            <VRow>
                                <VCol cols="12" md="6">
                                    <VTextField
                                        v-model="product.name"
                                        label="Name"
                                        outlined
                                        :error-messages="
                                            errorList.name
                                                ? [errorList.name[0]]
                                                : []
                                        "
                                    />
                                </VCol>
                                <VCol cols="12" md="6">
                                    <VTextarea
                                        v-model="product.description"
                                        label="Description"
                                        outlined
                                        :error-messages="
                                            errorList.description
                                                ? [errorList.description[0]]
                                                : []
                                        "
                                    />
                                </VCol>
                                <VCol cols="12" md="6">
                                    <VSelect
                                        v-model="product.selectedCategory"
                                        label="Select Category"
                                        :items="category"
                                        outlined
                                        multiple
                                        chips
                                        :error-messages="
                                            errorList.categoryIds
                                                ? [errorList.categorIds[0]]
                                                : []
                                        "
                                    />
                                </VCol>
                                <VCol cols="12" md="6">
                                    <VFileInput
                                        v-model="product.image"
                                        label="Image"
                                        outlined
                                        :error-messages="
                                            errorList.image
                                                ? [errorList.image[0]]
                                                : []
                                        "
                                        @change="uploadFile"
                                    />
                                </VCol>
                                <VCol cols="12" md="6">
                                    <VTextField
                                        v-model="product.price"
                                        label="Price (in Rs.)"
                                        outlined
                                        type="number"
                                        min="0"
                                        :error-messages="
                                            errorList.price
                                                ? [errorList.price[0]]
                                                : []
                                        "
                                    />
                                </VCol>
                                <VCol cols="12" md="6">
                                    <VLabel>Is Featured</VLabel>
                                    <VRadioGroup v-model="product.featured">
                                        <VRadio label="Yes" value="1" />
                                        <VRadio label="No" value="0" />
                                    </VRadioGroup>
                                </VCol>
                                <VCol cols="12" md="6">
                                    <VTextField
                                        v-model="product.slug"
                                        label="URL Slug"
                                        outlined
                                        :error-messages="
                                            errorList.url_slug
                                                ? [errorList.url_slug[0]]
                                                : []
                                        "
                                    />
                                </VCol>
                            </VRow>
                            <VBtn type="submit" color="primary"> Save </VBtn>
                        </VForm>
                    </VCol>
                </VRow>
            </VCardText>
        </VCard>
    </VContainer>
</template>

<script setup>
import axios from "axios";
import { ref, onMounted } from "vue";
import { useRouter } from "vue-router";
import Papa from "papaparse";
const router = useRouter();

const errorList = ref({});
const category = ref([]);

const product = ref({
    name: "",
    description: "",
    price: "",
    image: null,
    selectedCategory: [],
    slug: "",
    featured: "0",
});

const triggerFileInput = () => {
    const fileInput = document.getElementById("fileInput");
    fileInput.click();
};

const importProducts = async () => {
    const fileInput = document.getElementById("fileInput");
    const file = fileInput.files[0];
    console.log(file);
    if (file) {
        try {
            const fileExtension = file.name.split(".").pop().toLowerCase();

            if (fileExtension === "json") {
                // Handle JSON file
                const fileData = await readFileAsync(file);
                const importedProducts = JSON.parse(fileData);
                console.log(importedProducts);
                for (const importedProduct of importedProducts) {
                    // Set product values based on imported product data
                    product.value.name = importedProduct.name;
                    product.value.description = importedProduct.description;
                    product.value.price = importedProduct.price;
                    product.value.featured = importedProduct.is_featured;
                    product.value.slug = importedProduct.url_slug;
                    product.value.image = importedProduct.image_path;

                    // Set category (if available in imported product data)
                    product.value.selectedCategory =
                        importedProduct.categories || [];

                    // Save the product
                    await createProduct();
                }
            } else if (fileExtension === "csv") {
                // Handle CSV file
                Papa.parse(file, {
                    complete: async (result) => {
                        const importedProducts = result.data;
                        for (const importedProduct of importedProducts) {
                            // Set product values based on imported product data
                            product.value.name = importedProduct.name;
                            product.value.description =
                                importedProduct.description;
                            product.value.price = importedProduct.price;
                            product.value.featured = importedProduct.is_featured;
                            product.value.slug = importedProduct.url_slug;
                            product.value.image = importedProduct.image_path;

                            // Set category (if available in imported product data)
                            product.value.selectedCategory =
                                importedProduct.categories || [];
                            // Save the product
                            await createProduct();
                        }
                        console.log("Products imported successfully!");
                    },
                    header: true, // Assumes the first row contains headers
                });
            } else {
                console.error(
                    "Unsupported file format. Please use JSON or CSV."
                );
            }
        } catch (error) {
            console.error("Error importing products:", error);
        }
    }
};

const readFileAsync = (file) => {
    return new Promise((resolve, reject) => {
        const reader = new FileReader();

        reader.onload = (event) => {
            resolve(event.target.result);
        };

        reader.onerror = (error) => {
            reject(error);
        };

        reader.readAsText(file);
    });
};

const uploadFile = (event) => {
    product.value.image = event.target.files[0];
};

const fetchCategoryId = async () => {
    try {
        const response = await axios.get("/api/category");

        category.value = response.data.map((item) => item.name);

        return response.data
            .filter((item) =>
                product.value.selectedCategory.includes(item.name)
            )
            .map((item) => item.id);
    } catch (error) {
        console.error("Error fetching categories:", error);
    }
};

const createProduct = async () => {
    const categoryIds = await fetchCategoryId();
    if (!categoryIds) {
        console.error("Category IDs not found.");

        return;
    }

    const productData = new FormData();

    productData.append("name", product.value.name);
    productData.append("description", product.value.description);
    productData.append("price", product.value.price);
    productData.append("image", product.value.image);
    productData.append("url_slug", product.value.slug);
    productData.append("featured", product.value.featured);
    categoryIds.forEach((categoryId) => {
        productData.append("categoryIds[]", categoryId);
    });

    try {
        await axios.post("/api/products", productData);
        // alert("Product created successfully!");
        router.push({ path: "/admin/product" });
    } catch (error) {
        // console.error('Error creating product:', error)
        if (error.response && error.response.status === 422) {
            errorList.value = error.response.data.errors;
        }
    }
};

onMounted(fetchCategoryId);
</script>

<route lang="yaml">
meta:
    requiresAuth: true
    layout: AdminLayout
</route>
