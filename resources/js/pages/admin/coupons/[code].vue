<template>
    <div class="v-container mt-4">
        <VCard>
            <VRow align="center">
                <VCol>
                    <VCardTitle class="headline"> Edit Coupon </VCardTitle>
                </VCol>
                <VCol class="text-end">
                    <RouterLink to="/admin/coupons">
                        <VBtn color="secondary"> Back </VBtn>
                    </RouterLink>
                </VCol>
            </VRow>
            <VCardText>
                <VRow>
                    <VCol>
                        <VForm @submit.prevent="updateCoupon">
                            <!-- <VLabel>Coupon Option</VLabel>
                            <VRadioGroup v-model="coupon.option" inline>
                                <VRadio label="Automatic" value="automatic" />
                                <VRadio label="Manual" value="manual" />
                            </VRadioGroup> -->
                            <VTextField
                                v-model="coupon.code"
                                label="Coupon code"
                                outlined
                            />

                            <VLabel>Discopunt Type</VLabel>
                            <VRadioGroup v-model="coupon.discount_type" inline>
                                <VRadio label="Percentage" value="percentage" />
                                <VRadio label="Fixed" value="fixed" />
                            </VRadioGroup>
                            <VTextField
                                v-model="coupon.discount_amount"
                                label="Discount Amount"
                                type="number"
                                min="0"
                                outlined
                            />
                            <VTextField
                                v-model="coupon.start_date"
                                label="Start Date"
                                outlined
                                type="date"
                            />
                            <VTextField
                                v-model="coupon.end_date"
                                label="End Date"
                                outlined
                                type="date"
                            />
                            <VTextField
                                v-model="coupon.max_uses"
                                label="Maximum usage"
                                type="number"
                                min="1"
                                outlined
                            />
                            <VBtn type="submit" color="primary"> Save </VBtn>
                        </VForm>
                    </VCol>
                </VRow>
            </VCardText>
        </VCard>
    </div>
</template>

<script setup>
import { ref, onMounted } from "vue";
import { useRoute, useRouter } from "vue-router";
import axios from "axios";

const route = useRoute();
const router = useRouter();
const coupon = ref([]);

const updateCoupon = async () => {
    try {
        console.log(coupon.value)
        await axios.put(`/api/coupons/${coupon.value.id}`, coupon.value);
        alert("Coupon updated successfully!");
        router.push({ path: "/admin/coupons" });
    } catch (error) {
        console.error("Error updating coupon:", error);
    }
};

const fetchCoupon = async () => {
    try {
        const response = await axios.get(`/api/coupons/${route.params.code}`);
        coupon.value = response.data;
    } catch (error) {
        console.error("Error fetching product data:", error);
    }
};

onMounted(fetchCoupon);
// console.log(route.params.id)
</script>

<style lang="scss" scoped></style>

<route lang="yaml">
meta:
    requiresAuth: true
    layout: AdminLayout
</route>
