<script setup>
import SharedPrice from "../components/user/shared/SharedPrice.vue";
import CheckoutPromotionCode from "../components/user/checkout/CheckoutPromotionCode.vue";
import { ref, computed, onMounted } from "vue";
import { useRouter } from "vue-router";
import axios from "axios";
import { useCartStore } from "../stores/cart";
import { useAuthStore } from "../stores/auth";
import { loadStripe } from "@stripe/stripe-js";

//   let stripe: Stripe | null
//   let elements: StripeElements
const router = useRouter();
const authStore = useAuthStore();
const cartStore = useCartStore();
const userId = authStore.userData.id;
const totalPrice = computed(() => useCartStore().totalPrice);
const discountPrice = computed(() => useCartStore().discountPrice);
const couponDiscount = computed(() => useCartStore().couponDiscount);

// onMounted(async () => {
//     stripe = await loadStripe(config.public.STRIPE_KEY)
//     // Create Elements instance with options (do not include 'amount' here)
//     elements = stripe.elements({
//       mode: 'payment',
//       amount: totalPrice.value,
//       currency: 'inr',
// });

// Create payment element
// const paymentElement = elements.create('payment')

// Mount the payment element to the DOM
// paymentElement.mount('#payment-element')
//   })
const items = computed(() => useCartStore().items);
// const totalPrice = computed(() => useCartStore().totalPrice)

const form = ref({
    firstName: "",
    lastName: "",
    email: "",
    contact: "",
    address: "",
    postcode: "",
    city: "",
    country: "",
    state: "",
    name: "",
    user_id: "",
});

// const card = ref({
//   creditCardNumber: '',
//   expiryDate: '',
//   cvv: ''
// })
const creditCardNumber = ref("");
const expiryDate = ref("");
const cvv = ref("");

const selectedPaymentMethod = ref("");

const placeOrder = async () => {
    if (selectedAddress.value === "") {
        alert("Please select address...");
    } else if (totalPrice.value === 0) {
        alert("Please add product first");
    } else {
        selectedAddress.value = addresses.value.find(
            (address) => address.id === selectedAddress.value
        );
        if (selectedPaymentMethod.value === "cod") {
            await handlePaymentCOD();
        } else if (selectedPaymentMethod.value === "card") {
            await handlePaymentCard();
        }
        // console.log(selectedAddress.value);
    }
};

const handlePaymentCOD = async () => {
    try {
        const orderResponse = await axios.post("/api/orders", {
            userId: userId,
            totalAmount: discountPrice.value,
            paymentMethod: selectedPaymentMethod.value,
            status: "pending",
            cartItems: cartStore.items,
            orderAddress: selectedAddress.value,
        });
        router.replace({ path: "/orderConfirm" });
    } catch (error) {
        console.error("Error processing payment:", error);
    }
};

const handlePaymentCard = async () => {
    try {
        // Load Stripe.js on the client side
        const stripe = await loadStripe(
            "pk_test_51Oizf3GhrbvcuBv3831vrqUYfhdYtjtGf0lDnG1TjsOkPeyKoDZAa23gEPURAaAFUxsyysr6UT3YOsgs2JXDBNfg00rhh0fCG2"
        );

        // Step 1: Create a customer on Stripe
        const customerResponse = await axios.post(
            "https://api.stripe.com/v1/customers",
            {
                email: authStore.userData.email,
                name: authStore.userData.name,
            },
            {
                headers: {
                    Authorization:
                        "Bearer sk_test_51Oizf3GhrbvcuBv3ZlfHfeFrr5KKENo5MgiG5u7eqH2C06C2qt461Qs3f2C60ZNozxtiESlEO4ElwxxX2UmwkhOC00kasmyaX6",
                    "Content-Type": "application/x-www-form-urlencoded",
                },
            }
        );
        const customerId = customerResponse.data.id;

        // Step 2: Create a PaymentIntent
        const paymentIntentResponse = await axios.post(
            "https://api.stripe.com/v1/payment_intents",
            {
                currency: "usd",
                payment_method_types: ["card"],
                amount: discountPrice.value,
                payment_method: "pm_card_mastercard",
                customer: customerId,
            },
            {
                headers: {
                    Authorization:
                        "Bearer sk_test_51Oizf3GhrbvcuBv3ZlfHfeFrr5KKENo5MgiG5u7eqH2C06C2qt461Qs3f2C60ZNozxtiESlEO4ElwxxX2UmwkhOC00kasmyaX6",
                    "Content-Type": "application/x-www-form-urlencoded",
                },
            }
        );

        // Check if the API call was successful
        if (paymentIntentResponse.status !== 200) {
            console.error(
                "Error creating PaymentIntent:",
                paymentIntentResponse.data.error
            );
            return;
        }
        const { client_secret } = paymentIntentResponse.data;

        const { paymentIntent, error } = await stripe.confirmCardPayment(
            client_secret
        );
        if (paymentIntent) {
            if (paymentIntent.status === "succeeded") {
                const orderResponse = await axios.post("/api/orders", {
                    userId: userId,
                    // addressId: selectedAddress.value,
                    totalAmount: discountPrice.value,
                    paymentMethod: selectedPaymentMethod.value,
                    status: paymentIntent.status,
                    cartItems: cartStore.items,
                    orderAddress: selectedAddress.value,
                });
                router.replace({ path: "/orderConfirm" });
            } else if (paymentIntent.status === "requires_action") {
                // Payment still requires action, you may want to handle it further
                console.log("Payment requires additional action.");
            } else {
                // Payment failed, handle error
                console.error(error);
            }
        } else {
            // Handle the case where paymentIntent is undefined
            console.error("PaymentIntent is undefined:", error);
        }
    } catch (error) {
        console.error("Error handling payment:", error);
    }
};
// const creditCardChecked = ref(false);
//   const handleSubmit = async () => {
//     if (!stripe || !elements) {
//       return
//     }

// try {
//   const paymentIntentResponse = await axios.post(
//     'https://api.stripe.com/v1/payment_intents',
//     {
//       amount: totalPrice.value,
//       currency: 'inr',
//     },
//     {
//       headers: {
//         Authorization: 'Bearer sk_test_51OYpILSAVAOAOKC06T63CRPZOOp8OiCBNhFEFXIsUbtozvLpMr4mTGrsG0GHvCb8Zma5x4ZL5Jvqd9SZgQIynbUV00Fz4xpOgX',
//         'Content-Type': 'application/x-www-form-urlencoded',
//       },
//     },
//   )

// Assuming you are certain that the payment method is a card
//   const cardElement = elements.getElement('payment') as StripeCardElement

// Confirm the payment on the client side
//   const result = await stripe.confirmCardPayment(
//     paymentIntentResponse.data.client_secret,
//     {
//       payment_method: {
//         card: cardElement,
//       },
//     },
//   )

//   if (result.error) {
//     // Handle error (e.g., show an error message to the user)
//     console.error(result.error)
//   } else {
//     // Payment succeeded, you can perform further actions here
//     console.log(result.paymentIntent)
//   }
//     } catch (error) {
//       console.error(error)
//     }
//   }
const addAddress = async () => {
    try {
        form.value.user_id = userId;
        form.value.name = form.value.firstName + " " + form.value.lastName;
        const response = await axios.post("/api/auth/address", form.value);
        fetchAddresses();
    } catch (error) {
        console.error("Error storing user data:", error);
    }
};
const addresses = ref([]);
const selectedAddress = ref("");

const fetchAddresses = async () => {
    try {
        const response = await axios.get(`/api/auth/address/${userId}`);
        addresses.value = response.data;
    } catch (error) {
        console.error("Error fetching addresses:", error);
    }
};

onMounted(() => {
    fetchAddresses();
});

const editIndex = ref(null);
const editedAddress = ref({});

function editAddress(index) {
    editIndex.value = index;
    editedAddress.value = { ...addresses.value[index] };
}
function cancelEdit() {
    editIndex.value = null;
}

async function saveEdit() {
    console.log(editedAddress.value.id);
    try {
        const response = await axios.put(
            `/api/auth/address/${editedAddress.value.id}`,
            editedAddress.value
        );
        console.log(response.data);
        fetchAddresses();
    } catch (error) {
        console.error("Error fetching addresses:", error);
    }
    editIndex.value = null;
}

const showAddressForm = ref(false);

function toggleAddressForm() {
    showAddressForm.value = !showAddressForm.value;
}
</script>

<template>
    <div class="m-10">
        <div class="text-sm text-center text-secondary-600">
            <h2 style="font-size: 31px; font-weight: 600">CHECKOUT</h2>
        </div>
        <div class="checkout-inner mt-5">
            <div class="md:grid md:grid-cols-2 md:gap-6">
                <div class="md:col-span-1">
                    <div
                        class="grid gap-4 shadow px-4 py-5 bg-white sm:p-6 mb-8"
                    >
                        <div>
                            <h3
                                class="text-lg font-medium text-secondary-900 m-0"
                                style="font-size: 21px; font-weight: 600"
                            >
                                Address Details
                            </h3>
                            <br />
                            <div
                                v-for="(address, index) in addresses"
                                :key="address.id"
                                class="mb-6 border-b pb-4"
                            >
                                <div class="flex items-start justify-between">
                                    <div class="mr-4">
                                        <!-- class="text text-secondary-600" -->
                                        <label class="custom-label">
                                            <input
                                                type="radio"
                                                :value="address.id"
                                                v-model="selectedAddress"
                                                class="mr-2"
                                            />
                                            {{ address.name }}&nbsp;&nbsp;
                                            {{ address.contact }}
                                            <p class="text-secondary-600">
                                                &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;{{
                                                    address.address
                                                }}, {{ address.city }},
                                                {{ address.state }},
                                                {{ address.country }} -
                                                {{ address.postcode }}
                                            </p>
                                        </label>
                                    </div>
                                    <!-- hover:bg-primary-600 -->
                                    <button
                                        class="text-blue bg-white px-4 py-2 rounded"
                                        @click="editAddress(index)"
                                    >
                                        Edit
                                    </button>
                                </div>
                                <div v-if="editIndex === index">
                                    <!-- @click.outside="cancelEdit" -->
                                    <!-- class="absolute top-0 left-0 bg-white p-6 border rounded shadow-lg" -->
                                    <form @submit.prevent="saveEdit">
                                        <!-- Your edit form fields go here -->
                                        <div
                                            class="mt-3 grid grid-cols-6 gap-6"
                                        >
                                            <div
                                                class="col-span-6 sm:col-span-3"
                                            >
                                                <label
                                                    for="name"
                                                    class="block text-sm font-medium text-gray-700"
                                                    >Name</label
                                                >
                                                <input
                                                    v-model="editedAddress.name"
                                                    type="text"
                                                    id="name"
                                                    name="name"
                                                    class="mt-1 p-2 w-full border rounded-md"
                                                    required
                                                />
                                            </div>

                                            <div
                                                class="col-span-6 sm:col-span-3"
                                            >
                                                <label
                                                    for="contact"
                                                    class="block text-sm font-medium text-gray-700"
                                                    >Contact</label
                                                >
                                                <input
                                                    v-model="
                                                        editedAddress.contact
                                                    "
                                                    type="tel"
                                                    id="contact"
                                                    name="contact"
                                                    class="mt-1 p-2 w-full border rounded-md"
                                                    required
                                                />
                                            </div>

                                            <div
                                                class="col-span-6 sm:col-span-3"
                                            >
                                                <label
                                                    for="email"
                                                    class="block text-sm font-medium text-gray-700"
                                                    >Email</label
                                                >
                                                <input
                                                    v-model="
                                                        editedAddress.email
                                                    "
                                                    type="email"
                                                    id="email"
                                                    name="email"
                                                    class="mt-1 p-2 w-full border rounded-md"
                                                    required
                                                />
                                            </div>

                                            <div
                                                class="col-span-6 sm:col-span-3"
                                            >
                                                <label
                                                    for="postcode"
                                                    class="block text-sm font-medium text-gray-700"
                                                    >ZIP / Postal code</label
                                                >
                                                <input
                                                    v-model="
                                                        editedAddress.postcode
                                                    "
                                                    type="tel"
                                                    id="postcode"
                                                    name="postcode"
                                                    class="mt-1 p-2 w-full border rounded-md"
                                                    required
                                                />
                                            </div>

                                            <div class="col-span-6">
                                                <label
                                                    for="address"
                                                    class="block text-sm font-medium text-gray-700"
                                                    >Address</label
                                                >
                                                <input
                                                    v-model="
                                                        editedAddress.address
                                                    "
                                                    type="text"
                                                    id="address"
                                                    name="address"
                                                    class="mt-1 p-2 w-full border rounded-md"
                                                    required
                                                />
                                            </div>

                                            <div
                                                class="col-span-6 sm:col-span-3"
                                            >
                                                <label
                                                    for="city"
                                                    class="block text-sm font-medium text-gray-700"
                                                    >City</label
                                                >
                                                <input
                                                    v-model="editedAddress.city"
                                                    type="text"
                                                    id="city"
                                                    name="city"
                                                    class="mt-1 p-2 w-full border rounded-md"
                                                    required
                                                />
                                            </div>

                                            <div
                                                class="col-span-6 sm:col-span-3"
                                            >
                                                <label
                                                    for="state"
                                                    class="block text-sm font-medium text-gray-700"
                                                    >State</label
                                                >
                                                <input
                                                    v-model="
                                                        editedAddress.state
                                                    "
                                                    type="tel"
                                                    id="state"
                                                    name="state"
                                                    class="mt-1 p-2 w-full border rounded-md"
                                                    required
                                                />
                                            </div>
                                        </div>

                                        <div class="mt-2 flex justify-end">
                                            <button
                                                type="submit"
                                                class="px-4 py-2 bg-primary text-white rounded"
                                            >
                                                Save
                                            </button>
                                            <button
                                                type="button"
                                                @click="cancelEdit"
                                                class="ml-2 px-4 py-2 bg-gray-300 text-gray-700 rounded"
                                            >
                                                Cancel
                                            </button>
                                        </div>
                                        <!-- Add your edit form fields here -->
                                        <!-- <div
                                                v-if="editIndex === index"
                                                @click.outside="cancelEdit"
                                            >
                                                <form
                                                    @submit.prevent="saveEdit"
                                                >
                                                    <input
                                                        v-model="
                                                            editedAddress.name
                                                        "
                                                    />
                                                    <input
                                                        v-model="
                                                            editedAddress.email
                                                        "
                                                    />
                                                    <input
                                                        v-model="
                                                            editedAddress.contact
                                                        "
                                                    />
                                                    <input
                                                        v-model="
                                                            editedAddress.address
                                                        "
                                                    />
                                                    <input
                                                        v-model="
                                                            editedAddress.city
                                                        "
                                                    />

                                                    <button
                                                        type="submit"
                                                        class="text-white bg-primary-500 px-4 py-2 rounded"
                                                    >
                                                        Save
                                                    </button>
                                                </form>
                                            </div> -->
                                        <!-- Add other fields as needed -->
                                    </form>
                                </div>
                            </div>
                        </div>
                        <div
                            @click="toggleAddressForm"
                            class="flex items-center p-2 cursor-pointer bg-gradient-to-r from-blue-500 to-indigo-500 text-white hover:shadow-md rounded-lg transition duration-300"
                        >
                            <img
                                src="data:image/svg+xml;base64,PHN2ZyB3aWR0aD0iMTIiIGhlaWdodD0iMTIiIHZpZXdCb3g9IjAgMCAxMiAxMiIgeG1sbnM9Imh0dHA6Ly93d3cudzMub3JnLzIwMDAvc3ZnIj48ZyBmaWxsPSJub25lIiBmaWxsLXJ1bGU9ImV2ZW5vZGQiPjxwYXRoIGZpbGw9IiMyMTc1RkYiIGQ9Ik0xMS4yNSA2Ljc1aC00LjV2NC41aC0xLjV2LTQuNUguNzV2LTEuNWg0LjVWLjc1aDEuNXY0LjVoNC41Ii8+PHBhdGggZD0iTS0zLTNoMTh2MThILTMiLz48L2c+PC9zdmc+"
                                class="w-6 h-6 mr-3"
                                alt="Add Icon"
                            />
                            <span class="text font-semibold"
                                >Add a new address</span
                            >
                        </div>
                        <form
                            v-if="showAddressForm"
                            id="checkout-billing-address"
                            class="grid gap-8"
                            name="checkout-billing-address"
                            method="post"
                            @submit.prevent="addAddress"
                        >
                            <div class="grid grid-cols-6 gap-6">
                                <div class="col-span-6 sm:col-span-3">
                                    <label
                                        for="first-name"
                                        class="block text-sm font-medium text-secondary-700"
                                        >First name</label
                                    >
                                    <input
                                        id="first-name"
                                        v-model="form.firstName"
                                        type="text"
                                        required
                                        name="first-name"
                                        placeholder="Enter first name..."
                                        class="mt-1 block w-full p-2.5 border border-secondary-300 text-secondary-900 text-sm rounded-md shadow-sm focus:ring-brand-light focus:border-light"
                                        data-testid="checkout-pi-first-name-input"
                                    />
                                </div>

                                <div class="col-span-6 sm:col-span-3">
                                    <label
                                        for="last-name"
                                        class="block text-sm font-medium text-secondary-700"
                                        >Last name</label
                                    >
                                    <input
                                        id="last-name"
                                        v-model="form.lastName"
                                        type="text"
                                        required
                                        name="last-name"
                                        placeholder="Enter last name..."
                                        class="mt-1 block w-full p-2.5 border border-secondary-300 text-secondary-900 text-sm rounded-md shadow-sm focus:ring-brand-light focus:border-light"
                                        data-testid="checkout-pi-last-name-input"
                                    />
                                </div>
                                <div class="col-span-6 sm:col-span-3">
                                    <label
                                        for="email"
                                        class="block text-sm font-medium text-secondary-700"
                                        >Email</label
                                    >
                                    <input
                                        id="email"
                                        v-model="form.email"
                                        type="text"
                                        required
                                        name="email"
                                        placeholder="Enter email address..."
                                        class="mt-1 block w-full p-2.5 border border-secondary-300 text-secondary-900 text-sm rounded-md shadow-sm focus:ring-brand-light focus:border-light"
                                        data-testid="checkout-pi-last-name-input"
                                    />
                                </div>
                                <div class="col-span-6 sm:col-span-3">
                                    <label
                                        for="contact"
                                        class="block text-sm font-medium text-secondary-700"
                                    >
                                        Contact No.
                                    </label>
                                    <input
                                        id="contact"
                                        v-model="form.contact"
                                        type="tel"
                                        required
                                        name="contact"
                                        placeholder="Enter contact number..."
                                        class="mt-1 block w-full p-2.5 border border-secondary-300 text-secondary-900 text-sm rounded-md shadow-sm focus:ring-brand-light focus:border-light"
                                        data-testid="checkout-pi-last-name-input"
                                    />
                                </div>

                                <div class="col-span-6 sm:col-span-3">
                                    <label
                                        for="postal-code"
                                        class="block text-sm font-medium text-secondary-700"
                                    >
                                        ZIP / Postal code
                                    </label>
                                    <input
                                        id="postal-code"
                                        v-model="form.postcode"
                                        type="text"
                                        required
                                        name="postal-code"
                                        placeholder="Enter zip code..."
                                        autocomplete="postal-code"
                                        class="mt-1 block w-full p-2.5 border border-secondary-300 text-secondary-900 text-sm rounded-md shadow-sm focus:ring-brand-light focus:border-light"
                                        data-testid="checkout-pi-zip-code-input"
                                    />
                                </div>

                                <div class="col-span-6 sm:col-span-3">
                                    <label
                                        for="city"
                                        class="block text-sm font-medium text-secondary-700"
                                        >City</label
                                    >
                                    <input
                                        id="city"
                                        v-model="form.city"
                                        type="text"
                                        required
                                        name="city"
                                        placeholder="Enter city..."
                                        autocomplete="address-level2"
                                        class="mt-1 block w-full p-2.5 border border-secondary-300 text-secondary-900 text-sm rounded-md shadow-sm focus:ring-brand-light focus:border-light"
                                        data-testid="checkout-pi-city-input"
                                    />
                                </div>
                                <div class="col-span-6 sm:col-span-3">
                                    <label
                                        for="state"
                                        class="block text-sm font-medium text-secondary-700"
                                    >
                                        State
                                    </label>
                                    <input
                                        id="state"
                                        v-model="form.state"
                                        type="text"
                                        required
                                        name="state"
                                        placeholder="Enter state..."
                                        autocomplete="state"
                                        class="mt-1 block w-full p-2.5 border border-secondary-300 text-secondary-900 text-sm rounded-md shadow-sm focus:ring-brand-light focus:border-light"
                                        data-testid="checkout-pi-state-input"
                                    />
                                </div>
                                <div class="col-span-6 sm:col-span-3">
                                    <label
                                        for="country"
                                        class="block text-sm font-medium text-secondary-700"
                                    >
                                        Country
                                    </label>
                                    <input
                                        id="country"
                                        v-model="form.country"
                                        type="text"
                                        required
                                        name="country"
                                        placeholder="Enter country..."
                                        autocomplete="country"
                                        class="mt-1 block w-full p-2.5 border border-secondary-300 text-secondary-900 text-sm rounded-md shadow-sm focus:ring-brand-light focus:border-light"
                                        data-testid="checkout-pi-country-input"
                                    />
                                </div>

                                <div class="col-span-6">
                                    <label
                                        for="street-address"
                                        class="block text-sm font-medium text-secondary-700"
                                        >Address</label
                                    >
                                    <input
                                        id="street-address"
                                        v-model="form.address"
                                        type="text"
                                        required
                                        name="street-address"
                                        placeholder="Enter street..."
                                        autocomplete="street-address"
                                        class="mt-1 block w-full p-2.5 border border-secondary-300 text-secondary-900 text-sm rounded-md shadow-sm focus:ring-brand-light focus:border-light"
                                        data-testid="checkout-pi-street-address-input"
                                    />
                                </div>
                            </div>
                            <button
                                type="submit"
                                class="flex justify-center py-2 px-4 border border-transparent text-sm font-medium rounded-md text-white bg-primary hover:bg-dark focus:outline-none focus:ring-2 focus:ring-primary"
                                data-testid="checkout-pi-submit-button"
                            >
                                Save
                            </button>
                        </form>
                    </div>
                </div>
                <!-- <input
                :id="singlePaymentMethod.id"
                v-model="selectedPaymentMethod"
                :value="singlePaymentMethod.id"
                name="payment-method"
                type="radio"
                class="focus:ring-primary h-4 w-4 border-secondary-300"
                :data-testid="`checkout-payment-method-${singlePaymentMethod.id}`"
              />
              <label
                :for="singlePaymentMethod.id"
                :class="{ 'animate-pulse': isLoading[singlePaymentMethod.id] }"
                class="ml-2 block text-sm font-medium text-secondary-700 w-full"
              >
                <div class="flex justify-between">
                  <div>
                    <span>
                      {{ singlePaymentMethod.translated?.name }}
                    </span>
                    <span
                      v-if="singlePaymentMethod.translated?.description"
                      class="italic text-sm text-secondary-500 block"
                    >
                      {{ singlePaymentMethod.translated.description }}</span
                    >
                  </div>
                  <div v-if="singlePaymentMethod.media?.url">
                    <img
                      loading="lazy"
                      :src="singlePaymentMethod.media.url"
                      :alt="`Logo of ${singlePaymentMethod.shortName}`"
                    />
                  </div>
                </div>
              </label> -->

                <div class="md:mt-0 md:col-span-1">
                    <div class="grid gap-4 shadow px-4 py-5 bg-white sm:p-6">
                        <div>
                            <h3
                                class="text-lg font-medium text-secondary-900 m-0"
                                style="font-size: 21px; font-weight: 600"
                            >
                                Order summary
                            </h3>
                            <p class="text-sm text-secondary-600">
                                Order details and totals.
                            </p>
                        </div>
                        <br />

                        <div
                            class="flex justify-between text-sm text-secondary-500"
                        >
                            <p>Subtotal</p>
                            <SharedPrice
                                :value="totalPrice"
                                class="text-secondary-900 font-medium"
                                data-testid="cart-subtotal"
                            />
                        </div>

                        <div
                            class="flex pb-4 border-b justify-between text-sm text-secondary-500"
                        >
                            <p>Shipping estimate</p>
                            Free
                            <!-- <SharedPrice
                                :value="0"
                                class="text-secondary-900 font-medium"
                                data-testid="cart-subtotal"
                            /> -->
                        </div>

                        <div
                            v-if="couponDiscount"
                            class="flex pb-4 border-b justify-between text-sm text-secondary-500"
                        >
                            <p>
                                Coupon Discount: ({{ cartStore.coupon.code }})
                            </p>

                            <div>Rs. {{ couponDiscount }}</div>
                            <!-- <div v-else>Rs. 0</div> -->
                            <!-- <SharedPrice
                            :value="50"
                            class="text-secondary-900 font-medium"
                            data-testid="cart-subtotal"
                        /> -->
                        </div>

                        <div
                            class="flex justify-between text-secondary-900 font-medium"
                        >
                            <p>Order total</p>
                            <SharedPrice
                                :value="discountPrice"
                                data-testid="cart-subtotal"
                            />
                        </div>
                    </div>
                    <div
                        class="p-2 mt-4 justify-between text-secondary-900 font-medium"
                    >
                        <CheckoutPromotionCode />
                    </div>
                    <fieldset
                        class="grid gap-4 shadow px-4 py-5 bg-white sm:p-6"
                    >
                        <legend class="pt-5">
                            <h3
                                class="text-lg font-medium text-secondary-900 m-0"
                            >
                                Payment method
                            </h3>
                            <div class="text-sm text-secondary-600">
                                Select a payment method
                            </div>
                        </legend>
                        <div class="flex items-center">
                            <input
                                id="cc"
                                v-model="selectedPaymentMethod"
                                name="payment-method"
                                type="radio"
                                class="focus:ring-primary h-4 w-4 border-secondary-300"
                                data-testid="checkout-payment-method-cc"
                                value="card"
                            />
                            <label
                                for="cc"
                                class="ml-2 block text-sm font-medium text-secondary-700 w-full"
                                ><div class="flex justify-between">
                                    <div>
                                        <span>Card Payment</span>
                                    </div>
                                    <div>
                                        <img
                                            loading="lazy"
                                            src="https://cdn.shopware.store/a/B/m/pPkDE/media/26/e8/ac/1693898757/payment_app_MolliePaymentsApp_mollie_creditcard.png"
                                            alt="Logo of mollie_payments_app_mollie_creditcard"
                                        />
                                    </div></div
                            ></label>
                        </div>
                        <!-- <div id="payment-element"></div> -->
                        <div v-if="selectedPaymentMethod === 'card'">
                            <div class="flex items-center">
                                <div>
                                    <label
                                        for="creditCardNumber"
                                        class="block text-sm font-medium text-secondary-700"
                                        >Card Number</label
                                    >
                                    <input
                                        v-model="creditCardNumber"
                                        id="creditCardNumber"
                                        type="text"
                                        class="mt-1 p-2 border-secondary-300 w-full"
                                    />
                                </div>
                                <div class="ml-4">
                                    <label
                                        for="expiryDate"
                                        class="block text-sm font-medium text-secondary-700"
                                        >Expiry Date</label
                                    >
                                    <input
                                        v-model="expiryDate"
                                        id="expiryDate"
                                        type="text"
                                        class="mt-1 p-2 border-secondary-300"
                                    />
                                </div>
                                <div class="ml-4">
                                    <label
                                        for="cvv"
                                        class="block text-sm font-medium text-secondary-700"
                                        >CVV</label
                                    >
                                    <input
                                        v-model="cvv"
                                        id="cvv"
                                        type="text"
                                        class="mt-1 p-2 border-secondary-300"
                                    />
                                </div>
                            </div>
                        </div>
                        <div class="flex items-center">
                            <input
                                id="cod"
                                v-model="selectedPaymentMethod"
                                name="payment-method"
                                type="radio"
                                class="focus:ring-primary h-4 w-4 border-secondary-300"
                                data-testid="checkout-payment-method-cod"
                                value="cod"
                            />
                            <label
                                for="cod"
                                class="ml-2 block text-sm font-medium text-secondary-700 w-full animate-pulse"
                                ><div class="flex justify-between">
                                    <div>
                                        <span>Cash on delivery</span
                                        ><span
                                            class="italic text-sm text-secondary-500 block"
                                            >Payment upon receipt of
                                            goods.</span
                                        >
                                    </div>
                                </div></label
                            >
                        </div>
                        <div class="mt-4">
                            <div class="text-right">
                                <button
                                    v-if="items"
                                    type="button"
                                    class="w-full flex justify-center py-2 px-4 border border-transparent font-medium rounded-md text-white bg-primary focus:outline-none focus:ring-2 focus:ring-primary"
                                    data-testid="checkout-place-order-button"
                                    @click="placeOrder"
                                >
                                    Place the order
                                </button>
                            </div>
                        </div>
                    </fieldset>
                </div>
            </div>
        </div>
    </div>
</template>

<route lang="yaml">
meta:
    requiresAuth: true
</route>
