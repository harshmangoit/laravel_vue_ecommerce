import { defineStore } from "pinia";
import { ref, computed } from "vue";

export const useCartStore = defineStore("cart", () => {
    const items = ref(getStoredCartItems());
    const coupon = ref(getStoredCoupon());
    // const couponApplied = localStorage.getItem("coupon") == null;

    const totalItems = computed(() => {
        if (items.value) {
            return items.value.length;
        }
    });
    const couponDiscount = computed(() => {
        if (coupon.value) {
            return parseFloat(coupon.value.discount_amount);
        }
    });
    const totalPrice = computed(() => {
        return items.value.reduce(
            (total, item) => total + item.price * item.quantity,
            0
        );

        // Apply coupon discount if a coupon is applied
        // const discount = coupon.value ? coupon.value.discount_amount : 0;
        // return itemsTotal - discount;
    });
    const discountPrice = computed(() => {
        // const itemsTotal = items.value.reduce(
        //     (total, item) => total + item.price * item.quantity,
        //     0
        // );

        // Apply coupon discount if a coupon is applied
        const discount = coupon.value ? coupon.value.discount_amount : 0;
        return totalPrice.value - discount;
    });

    function addItem(product) {
        const existingItem = items.value.find((item) => item.id === product.id);
        if (existingItem) {
            existingItem.quantity += 1;
        } else {
            items.value.push({ ...product, quantity: 1 });
        }
        saveCartToStorage();
    }

    function removeItem(productId) {
        items.value = items.value.filter((item) => item.id !== productId);
        saveCartToStorage();
    }

    function clearCart() {
        items.value = [];
        saveCartToStorage();
    }

    function applyCoupon(couponData) {
        coupon.value = couponData;
        saveCouponToStorage();
    }

    function removeCoupon() {
        coupon.value = null;
        saveCouponToStorage();
    }

    function saveCouponToStorage() {
        if (typeof localStorage !== "undefined") {
            localStorage.setItem("coupon", JSON.stringify(coupon.value));
        }
    }

    function saveCartToStorage() {
        if (typeof localStorage !== "undefined") {
            localStorage.setItem("cartItems", JSON.stringify(items.value));
        }
    }

    return {
        items,
        totalItems,
        totalPrice,
        addItem,
        removeItem,
        clearCart,
        applyCoupon,
        removeCoupon,
        discountPrice,
        coupon,
        couponDiscount,
        // couponApplied,
    };
});

function getStoredCartItems() {
    if (typeof localStorage !== "undefined") {
        const storedItems = localStorage.getItem("cartItems");
        return storedItems ? JSON.parse(storedItems) : [];
    }
    return [];
}

function getStoredCoupon() {
    if (typeof localStorage !== "undefined") {
        const couponData = localStorage.getItem("coupon");
        return couponData ? JSON.parse(couponData) : null;
    }
    return null;
}
