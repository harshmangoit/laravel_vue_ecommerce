<template>
    <VContainer class="mt-5">
        <VCard>
            <VRow align="center">
                <VCol>
                    <VCardTitle class="headline"> Edit Category </VCardTitle>
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
                        <VForm @submit.prevent="updateCategory">
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
                                    <VLabel>Status</VLabel>
                                    <VRadioGroup v-model="form.status">
                                        <VRadio label="Active" value="1" />
                                        <VRadio label="Inactive" value="0" />
                                    </VRadioGroup>
                                </VCol>

                                <VCol cols="12" md="6">
                                    <VTextField
                                        v-model="form.slug"
                                        label="Slug"
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
import { ref, onMounted } from "vue";
import { useRoute, useRouter } from "vue-router";

const route = useRoute();
const router = useRouter();
const status = ref("0");

const isLoading = ref(false);
const isLoadingTitle = ref("Loading");
const errorList = ref({});

const form = ref({
    name: "",
    status: "",
    slug: "",
    position: "",
});

const fetchCategory = async () => {
    try {
        const response = await axios.get(`/api/category/${route.params.slug}`);
        console.log(response.data);
        form.value = response.data;
        form.value.status = String(response.data.status);
    } catch (error) {
        console.error("Error fetching category:", error);
    }
};

onMounted(fetchCategory);

const updateCategory = async () => {
    isLoading.value = true;
    isLoadingTitle.value = "Saving";
    try {
        await axios.put(`/api/category/${route.params.slug}`, form.value, {});
        alert("Category updated successfully!");
        router.push({ path: "/admin/category" });
    } catch (error) {
        console.error("Error updating category:", error);
    }
};

//   useHead({
//     title: 'Category Edit',
//   })

//   definePageMeta({
//     middleware: 'auth',
//   })
</script>

<route lang="yaml">
meta:
    requiresAuth: true
    layout: AdminLayout
</route>
