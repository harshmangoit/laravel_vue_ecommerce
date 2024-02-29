<script setup>
import axios from "axios";
import { ref, onMounted } from "vue";
import { useCartStore } from "../../../stores/cart";

const cartStore = useCartStore();
// const totalPrice = computed(() => useCartStore().totalPrice);

const coupon = ref({});
const promoCode = ref("");
const promoCodeError = ref(null);
const promoCodeSuccess = ref(null);
// const promoCodeApplied = ref(false);
const havePromoCode = ref("");

const clearPromoCodeError = () => {
    promoCodeError.value = null;
    promoCodeSuccess.value = null;
};

const clearPromoCode = () => {
    // const cartStore = useCartStore();

    cartStore.removeCoupon();
    // Function to clear the entered coupon code
    havePromoCode.value = "";
    promoCode.value = "";
    promoCodeSuccess.value = null;
    // promoCodeSuccess.value = "";
    // promoCodeApplied.value = false;
    // promoCodeError.value = "";
    // coupon.value = {};
};

const verifyCoupon = () => {
    // Function to verify the entered coupon code
    const currentDate = new Date();
    const startDate = new Date(coupon.value.start_date);
    const endDate = new Date(coupon.value.end_date);

    // Check if the coupon code is not expired
    if (currentDate < startDate || currentDate > endDate) {
        promoCodeError.value = "Coupon code has expired.";
        return false;
    }

    // Check if the coupon has reached its maximum uses
    if (coupon.value.current_uses >= coupon.value.max_uses) {
        promoCodeError.value = "Coupon code has reached its maximum uses.";
        return false;
    }

    // Additional verification logic based on your requirements

    // If all verification checks pass, return true
    return true;
};

const couponDiscount = () => {
    cartStore.applyCoupon(coupon.value);
    // if (coupon.value.discount_applied) {
    // Coupon has already been applied, do nothing
    // return;
    // }
    // if (coupon.value.discount_type === "fixed") {
    //     // totalPrice.value = totalPrice.value - coupon.value.discount_amount;
    //     // console.log(coupon.value);
    //     useCartStore().applyCoupon({
    //         discount_amount: coupon.value.discount_amount,
    //     });
    // } else if (coupon.value.discount_type === "percentage") {
    //     // Convert the percentage to a decimal
    //     const percentageDecimal = coupon.value.discount_amount / 100;
    //     // Calculate the discounted amount based on the percentage
    //     const discountAmount = useCartStore().totalPrice * percentageDecimal;
    //     console.log(discountAmount);

    // Apply the discount to the cart store
    // useCartStore().applyCoupon({
    //     discount_amount: discountAmount,
    // });

    // Mark the coupon as applied to prevent multiple applications
    // coupon.value.discount_applied = true;
    // }
};

const applyPromoCode = async () => {
    if (!promoCode.value.trim()) {
        promoCodeError.value = "Please enter a coupon code.";
        return;
    }
    try {
        const response = await axios.get(`/api/coupons/${promoCode.value}`);
        coupon.value = response.data;
        console.log(coupon.value)
        if (!coupon.value.code) {
            console.log('hi')
            promoCodeError.value = "Invalid coupon code. Please try again.";
        } else if (verifyCoupon()) {
            havePromoCode.value = promoCode.value;
            // promoCodeApplied.value = true;
            couponDiscount();
            promoCodeSuccess.value = "Coupon applied successfully!";
        } 
        // else {
        //     promoCodeError.value = "Invalid coupon code. Please try again.";
        // }
    } catch (error) {
        console.error("Error fetching coupon:", error);
    }
};
// await axios.get(`/api/coupons/${promoCode.value}`).then((response) => {
//     coupon.value = response.data;
//     if (!response.data.code) {
//         promoCodeError.value = "Invalid coupon code. Please try again.";
//     } else if (verifyCoupon()) {
//         havePromoCode.value = promoCode.value;

//         couponDiscount();
//         // Coupon is valid, apply the discount or perform any other necessary actions
//         // For example, you can update the UI, calculate the discounted total, etc.
//         promoCodeSuccess.value = "Coupon applied successfully!";
//     } else {
//         // Coupon is not valid, display an error message
//         promoCodeError.value = "Invalid coupon code. Please try again.";
//     }
// });

// if (verifyCoupon()) {
//     havePromoCode.value = promoCode.value;

//     couponDiscount();
//     // Coupon is valid, apply the discount or perform any other necessary actions
//     // For example, you can update the UI, calculate the discounted total, etc.
//     console.log("Coupon applied successfully!");
// } else {
//     // Coupon is not valid, display an error message
//     promoCodeError.value = "Invalid coupon code. Please try again.";
// }

// const response = await axios.get(`/api/coupons/${promoCode.value}`);
// if (!response.data.code) {
//     promoCodeError.value = "Please enter valid coupon code.";
// }

onMounted(() => {
    if (cartStore.coupon) {
        promoCode.value = cartStore.coupon.code;
    }
});
</script>

<template>
    <div>
        <div class="mb-4">
            <div class="text-sm pb-2">
                Enter your coupon code if you have one.
            </div>
            <div class="flex gap-3">
                <input
                    v-model="promoCode"
                    @input="clearPromoCodeError"
                    type="text"
                    name="promoCode"
                    placeholder="Enter coupon code"
                    class="border rounded-md py-2 px-4 border-solid border-1 border-cyan-600 w-full"
                />
                <span
                    v-if="cartStore.coupon"
                    class="cursor-pointer"
                    @click="clearPromoCode"
                >
                    <svg
                        class="w-6 h-6 fill-current text-red-500"
                        xmlns="http://www.w3.org/2000/svg"
                        viewBox="0 0 24 24"
                    >
                        <path
                            d="M19 6.41L17.59 5 12 10.59 6.41 5 5 6.41 10.59 12 5 17.59 6.41 19 12 13.41 17.59 19 19 17.59 13.41 12z"
                        />
                    </svg>
                </span>
                <button
                    v-else
                    class="text-black bg-primary-500 hover:bg-primary-600 justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md"
                    @click="applyPromoCode"
                >
                    Apply
                </button>
            </div>
            <span v-if="promoCodeError" class="ml-4 error-msg">
                {{ promoCodeError }}
            </span>
            <span v-if="promoCodeSuccess" class="ml-4 success-msg">
                {{ promoCodeSuccess }}
            </span>
        </div>
    </div>
</template>

<style scoped>
.error-msg {
    color: red;
    font-size: 14px;
}
.success-msg {
    color: green;
    font-size: 15px;
}
</style>
