<!-- ProductForm.vue -->
<template>
    <VContainer class="mt-3">
        <VCard>
            <VRow align="center">
                <VCol>
                    <VCardTitle class="headline"> Edit Product </VCardTitle>
                </VCol>
                <VCol class="text-end">
                    <RouterLink to="/admin/product">
                        <VBtn color="secondary"> Back </VBtn>
                    </RouterLink>
                </VCol>
            </VRow>
            <VCardText>
                <VForm
                    enctype="multipart/form-data"
                    @submit.prevent="updateProduct"
                >
                    <VCol cols="12" md="6">
                        <VTextField
                            v-model="product.name"
                            label="Name"
                            outlined
                            :error-messages="
                                errorList.name ? [errorList.name[0]] : []
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
                                errorList.categories
                                    ? [errorList.categories[0]]
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
                                errorList.image ? [errorList.image[0]] : []
                            "
                            @change="uploadFile"
                        />
                    </VCol>
                    <VCol cols="12" md="6">
                        <img
                            :src="'http://127.0.0.1:8000' + product.image_path"
                            alt="Product Image"
                            height="90px"
                            width="100px"
                        />
                    </VCol>
                    <VCol cols="12" md="6">
                        <VTextField
                            v-model="product.price"
                            label="Price (in Rs.)"
                            outlined
                            type="number"
                            :error-messages="
                                errorList.price ? [errorList.price[0]] : []
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
                    <VBtn type="submit" color="primary"> Save </VBtn>
                </VForm>
            </VCardText>
        </VCard>
    </VContainer>
</template>

<script setup>
import { ref, onMounted } from "vue";
import { useRoute, useRouter } from "vue-router";
import axios from "axios";

const route = useRoute();
const router = useRouter();

const errorList = ref({});
const category = ref([]);
const categories = ref([]);

const product = ref({
    name: "",
    description: "",
    price: "",
    image: null,
    image_path: "",
    selectedCategory: [],
    featured: "",
    slug: "",
});

const uploadFile = (event) => {
    image.value = event.target.files[0];
};

const fetchProductById = async () => {
    try {
        const response = await axios.get(`/api/products/${route.params.slug}`);

        product.value = response.data.product;
        product.value.slug = response.data.product.url_slug;
        product.value.featured = String(response.data.product.is_featured);
        categories.value = response.data.categories;
        category.value = response.data.categories.map((item) => item.name);
        product.value.selectedCategory = response.data.selectedCategory;

        // return response.data.categories
        //   .filter(item => product.value.selectedCategory.includes(item.name))
        //   .map(item => item.id)
    } catch (error) {
        console.error("Error fetching product data:", error);
    }
};

onMounted(fetchProductById);

const updateProduct = async () => {
    // const categoryIds = await fetchProductById()

    const filteredCategories = categories.value.filter((item) =>
        product.value.selectedCategory.includes(item.name)
    );

    const categoryIds = filteredCategories.map((item) => item.id);

    const productData = new FormData();

    productData.append("name", product.value.name);
    productData.append("description", product.value.description);
    productData.append("price", product.value.price);
    productData.append("image", product.value.image);
    productData.append("slug", product.value.slug);
    productData.append("featured", product.value.featured);
    categoryIds.forEach((categoryId) => {
        productData.append("category[]", categoryId);
    });
    productData.append("_method", "put");
    try {
        await axios.post(`/api/products/${route.params.slug}`, productData, {
            headers: { "Content-Type": "multipart/form-data" },
        });
        alert("Product updated successfully!");
        router.push({ path: "/admin/product" });
    } catch (error) {
        console.error("Error creating product:", error);
    }
};
</script>

<route lang="yaml">
meta:
    requiresAuth: true
    layout: AdminLayout
</route>
