<template>
    <VContainer class="mt-5">
        <VCard>
            <VRow align="center">
                <VCol>
                    <VCardTitle class="headline"> Add Category </VCardTitle>
                </VCol>
                <VCol class="text-end">
                    <RouterLink to="/admin/category">
                        <VBtn color="secondary"> Back </VBtn>
                    </RouterLink>
                </VCol>
            </VRow>
            <VCardText>
                <VRow>
                    <VCol v-if="isLoading">
                        <!-- <Loading :title="isLoadingTitle" /> -->
                    </VCol>
                    <VCol v-else>
                        <VForm @submit.prevent="createCategory">
                            <VRow>
                                <VCol cols="12" md="6">
                                    <VTextField
                                        v-model="form.name"
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
                                    <VTextField
                                        v-model="form.slug"
                                        label="URL Slug"
                                        outlined
                                        :error-messages="
                                            errorList.slug
                                                ? [errorList.slug[0]]
                                                : []
                                        "
                                    />
                                </VCol>

                                <VCol cols="12" md="6">
                                    <VTextField
                                        v-model="form.position"
                                        label="Position"
                                        outlined
                                        type="number"
                                        min="0"
                                        :error-messages="
                                            errorList.position
                                                ? [errorList.position[0]]
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
import { ref } from "vue";
import { useRouter } from "vue-router";
const router = useRouter();

const isLoading = ref(false);
const isLoadingTitle = ref("Loading");
const errorList = ref({});

const form = ref({
    name: "",
    slug: "",
    position: "",
});

const createCategory = async () => {
    isLoading.value = true;
    isLoadingTitle.value = "Saving";
    try {
        await axios.post("/api/category", form.value);
        isLoading.value = false;
        isLoadingTitle.value = "Loading";
        alert("Category created successfully!");
        router.push({ path: "/admin/category" });
    } catch (error) {
        console.error("Error creating category:", error);
        if (error.response.status == 422) {
            errorList.value = error.response.data.errors;
        }
        isLoading.value = false;
    }
};

// useHead({
//     title: "Category Create",
// });

// definePageMeta({
//     middleware: "auth",
// });
</script>

<route lang="yaml">
meta:
    requiresAuth: true
    layout: AdminLayout
</route>
