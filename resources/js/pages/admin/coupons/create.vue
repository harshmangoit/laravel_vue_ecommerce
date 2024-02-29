<template>
    <div class="v-container mt-4">
        <VCard>
            <VRow align="center">
                <VCol>
                    <VCardTitle class="headline"> Add Coupon </VCardTitle>
                </VCol>
                <VCol class="text-end">
                    <RouterLink to="/admin/coupons">
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
                        <VForm @submit.prevent="createCoupon">
                            <!-- <VRow> -->
                            <!-- <VCol cols="12" md="6"> -->
                            <VLabel>Coupon Option</VLabel>
                            <VRadioGroup v-model="coupon.option" inline>
                                <VRadio label="Automatic" value="automatic" />
                                <VRadio label="Manual" value="manual" />
                            </VRadioGroup>
                            <VTextField
                                v-if="coupon.option == 'manual'"
                                v-model="coupon.code"
                                label="Coupon code"
                                outlined
                            />
                            <!-- :error-messages="
                                            errorList.name
                                                ? [errorList.name[0]]
                                                : []
                                        " -->
                            <!-- </VCol> -->

                            <!-- <VCol cols="12" md="6"> -->
                            <!-- <VTextField
                                        v-model="coupon.discountType"
                                        label="Discout Type"
                                        type="number"
                                        min="1"
                                        outlined
                                    /> -->
                            <!-- <VCol cols="12" md="6"> -->
                            <VLabel>Discopunt Type</VLabel>
                            <VRadioGroup v-model="coupon.discountType" inline>
                                <VRadio label="Percentage" value="percentage" />
                                <VRadio label="Fixed" value="fixed" />
                            </VRadioGroup>
                            <!-- </VCol> -->
                            <!-- </VCol> -->

                            <!-- <VCol cols="12" md="6"> -->
                            <VTextField
                                v-model="coupon.discountAmount"
                                label="Discount Amount"
                                type="number"
                                min="0"
                                outlined
                            />
                            <!-- :error-messages="
                                            errorList.slug
                                                ? [errorList.slug[0]]
                                                : []
                                        " -->
                            <!-- </VCol> -->

                            <!-- <VCol cols="12" md="6"> -->
                            <VTextField
                                v-model="coupon.startDate"
                                label="Start Date"
                                outlined
                                type="date"
                            />
                            <!-- :error-messages="
                                            errorList.position
                                                ? [errorList.position[0]]
                                                : []
                                        " -->
                            <!-- </VCol> -->

                            <!-- <VCol cols="12" md="6"> -->
                            <VTextField
                                v-model="coupon.endDate"
                                label="End Date"
                                outlined
                                type="date"
                            />
                            <!-- </VCol> -->

                            <!-- <VCol cols="12" md="6"> -->
                            <VTextField
                                v-model="coupon.maxUses"
                                label="Maximum usage"
                                type="number"
                                min="1"
                                outlined
                            />
                            <!-- </VCol> -->

                            <!-- <VCol cols="12" md="6">
                                    <VTextField
                                        v-model="coupon.currentUses"
                                        label="Current uses"
                                        type="number"
                                        min="0"
                                        outlined
                                    />
                                </VCol> -->
                            <!-- </VRow> -->
                            <VBtn type="submit" color="primary"> Save </VBtn>
                        </VForm>
                    </VCol>
                </VRow>
            </VCardText>
        </VCard>
    </div>
</template>

<script setup>
import { ref } from "vue";
import { useRouter } from "vue-router";
import axios from "axios";

const router = useRouter();

const isLoading = ref(false);
const isLoadingTitle = ref("Loading");
const errorList = ref({});

const coupon = ref({
    option: "manual",
    code: "",
    discountType: "",
    discountAmount: "",
    startDate: "",
    endDate: "",
    maxUses: "",
    // currentUses: "",
});

const generateCoupon = () => {
    coupon.value.code = generateRandomCouponCode();
};

const generateRandomCouponCode = () => {
    // Generate a random string with alphanumeric characters
    const codeLength = 6;
    const characters = "ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789";
    let code = "";

    for (let i = 0; i < codeLength; i++) {
        const randomIndex = Math.floor(Math.random() * characters.length);
        code += characters[randomIndex];
    }

    return code;
};

const createCoupon = async () => {
    isLoading.value = true;
    isLoadingTitle.value = "Saving";
    if (coupon.value.option === "automatic") {
        generateCoupon();
    }
    try {
        await axios.post("/api/coupons", coupon.value);
        isLoading.value = false;
        isLoadingTitle.value = "Loading";
        router.push({ path: "/admin/coupons" });
    } catch (error) {
        if (error.response.status == 422) {
            errorList.value = error.response.data.errors;
        }
        isLoading.value = false;
    }
};
</script>

<style lang="scss" scoped></style>

<route lang="yaml">
meta:
    requiresAuth: true
    layout: AdminLayout
</route>
