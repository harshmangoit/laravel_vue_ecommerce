<script setup>
import { useCartStore } from "../../stores/cart";
import { useAuthStore } from "../../stores/auth";
import { ref, onMounted } from "vue";
import { useRoute } from "vue-router";
import axios from "axios";

const route = useRoute();
const cartStore = useCartStore();
const authStore = useAuthStore();
const userId = authStore.isLoggedIn ? authStore.userData.id : null;
const product = ref({});
const breadcrumbItems = ref([]);
const reviews = ref([]);
const addedToWishlist = ref(false);
const userWishlist = ref([]);

const alreadyInCart = (product) => {
    const x = cartStore.items?.find((item) => item.id === product.id);
    if (x?.id) {
        return true;
    } else {
        return false;
    }
};

const addToCart = () => {
    if (!alreadyInCart(product.value)) {
        cartStore.addItem(product.value);
    } else {
        alert(`${product.value.name} already in cart`);
    }
};

const fetchProductBySlug = async () => {
    const response = await axios.get(`/api/products/${route.params.slug}`);
    product.value = response.data.product;
    breadcrumbItems.value = [
        {
            title: "Home",
            disabled: false,
            href: "/",
        },
        {
            title: response.data.product.category[0].name,
            disabled: false,
            href: "/" + response.data.product.category[0].slug,
        },
        {
            title: product.value.name,
            disabled: true,
        },
    ];
    fetchReviews();
};

const checkWishlist = async () => {
    const isProductInWishlist = userWishlist.value.some(
        (item) => item.product_id === product.value.id
    );

    if (isProductInWishlist) {
        // If the product is already in the wishlist, set addedToWishlist to true
        addedToWishlist.value = true;
    }
};

const fetchUserWishlist = async () => {
    await axios
        .get(`/api/wishlists/${userId}`)
        .then((response) => {
            userWishlist.value = response.data;
        })
        .catch((error) => {
            console.error("Error fetching user wishlist:", error);
        });
    checkWishlist();ist.value = false;
};

const addToWishlist = async () => {
    await fetchUserWishlist();
    try {
        // Check if the product is already in the user's wishlist
        const isProductInWishlist = userWishlist.value.some(
            (item) => item.product_id === product.value.id
        );

        if (isProductInWishlist) {
            // If the product is already in the wishlist, set addedToWishlist to true
            addedToWishlist.value = true;
        } else {
            // If the product is not in the wishlist, add it
            const formData = new FormData();
            formData.append("user_id", userId);
            formData.append("product_id", product.value.id);

            // Make a POST request to add the product to the wishlist
            await axios.post("/api/wishlists", formData);

            // Set addedToWishlist to true after successful addition
            addedToWishlist.value = true;
        }
    } catch (error) {
        // Handle any errors that may occur during the request
        console.error("Error adding product to wishlist:", error);
    }
};
const removeFromWishlist = async () => {
    await fetchUserWishlist();
    try {
        // Find the index of the product in the wishlist array
        const productIndex = userWishlist.value.findIndex(
            (item) => item.product_id === product.value.id
        );
        if (productIndex !== -1) {
            // If the product is in the wishlist, remove it
            await axios.delete(
                `/api/wishlists/${userWishlist.value[productIndex].id}`
            );

            // Set addedToWishlist to false after successful removal
            addedToWishlist.value = false;
        }
    } catch (error) {
        // Handle any errors that may occur during the request
        console.error("Error removing product from wishlist:", error);
    }
};

const fetchReviews = async () => {
    try {
        const response = await axios.get(
            `/api/product/reviews/${product.value.id}`
        );
        reviews.value = response.data;
    } catch (error) {
        console.error("Error fetching product data:", error);
    }
};

const calculateAverageRating = () => {
    const totalReviews = reviews.value.length;
    const sumReviews = reviews.value.reduce(
        (sum, review) => sum + review.rating,
        0
    );
    const averageReviews =
        totalReviews > 0 ? Math.round(sumReviews / totalReviews) : 0;
    return averageReviews; // Return the actual average rating value
};

function formatDate(dateTimeString) {
    const options = {
        month: "short",
        day: "numeric",
        year: "numeric",
    };

    const date = new Date(dateTimeString);
    return date.toLocaleString("en-US", options);
}

onMounted(() => {
    fetchProductBySlug();
    fetchUserWishlist();
});

const colors = [
    {
        id: 1,
        name: "Gray",
    },
    {
        id: 2,
        name: "Black",
    },
    {
        id: 3,
        name: "White",
    },
    {
        id: 4,
        name: "Blue",
    },
];

const selected = ref(colors[0]);
</script>
<template>
    <div class="v-container">
    <section class="product-details mb-10">
        <div class="flex justify-center max-h-96 xl:max-h-[600px]">
            <div class="-m-4 bg-white p-8 rounded-lg w-full">
                <div class="flex items-center justify-between">
                    <v-breadcrumbs  :items="breadcrumbItems">
                        <template v-slot:divider>
                            <v-icon icon="mdi-chevron-right"></v-icon>
                        </template>
                    </v-breadcrumbs>
                    <div v-if="authStore.isLoggedIn">
                        <v-btn
                            style="width: 1px; height: 60px; border-radius: 50%"
                            v-if="!addedToWishlist"
                            @click="addToWishlist"
                        >
                            <v-icon>mdi-heart-outline</v-icon>
                        </v-btn>
                        <v-btn
                            style="width: 1px; height: 60px; border-radius: 50%"
                            v-else
                            @click="removeFromWishlist"
                        >
                            <v-icon color="red">mdi-heart</v-icon>
                        </v-btn>
                    </div>
                </div>
                <v-img
                    :src="`http://127.0.0.1:8000` + product.image_path"
                    width="500"
                    height="300"
                    class="max-h-full mx-auto d-block"
                ></v-img>
                <div class="flex mt-8 w-full">
                    <v-btn
                        label="Add To Cart"
                        class="rounded-lg w-50 h-50 max-h-full mx-auto d-block"
                        color="#ff9f00"
                        size="x-large"
                        @click="addToCart"
                    >
                        <span class="py-2 lg:text-lg text-white">{{
                            alreadyInCart(product) ? "In Cart" : "Add to cart"
                        }}</span>
                    </v-btn>
                </div>
            </div>
        </div>
        <div class="mt-16 md:mt-0 md:mx-8">
            <div class="relative">
                <h1 class="mt-2 text-xl font-bold">{{ product.name }}</h1>
            </div>
            <div class="flex items-center justify-between mt-1">
                <div class="flex items-center">
                    <div class="ml-4"></div>
                </div>
            </div>
            <div>
                <span class="font-bold text-xl">Rs. {{ product.price }}</span>
            </div>

            <!-- <div class="mt-4">
                    <v-select
                        v-model="selected"
                        :items="colors"
                        item-title="name"
                        item-value="id"
                        variant="outlined"
                    >
                    </v-select>
                </div> -->
            <div class="mt-2">
                <span>{{ product.description }}</span>
            </div>
            <div class="md:mr-4 mt-2"><span>Best Deals Inc.</span></div>
            <div class="p-4 bg-gray-100 border shadow-md mt-8">
                <div class="flex items-center justify-between p-3">
                    <div class="text-xl font-semibold">
                        Ratings &amp; Reviews
                    </div>
                    <div class="flex items-center">
                        <div class="mr-2">
                            <span
                                v-for="index in 5"
                                :key="index"
                                :class="{
                                    'text-2xl cursor-pointer': true,
                                    'text-yellow-500':
                                        index <= calculateAverageRating(),
                                }"
                                >★</span
                            >
                        </div>
                    </div>
                </div>
            </div>
            <div class="p-4 border shadow-md" v-for="review in reviews">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 p-3">
                    <div class="col-span-2 md:col-span-1 mt-2">
                        <div class="flex items-center">
                            <div
                                class="m-1 p-1 text-lg font-semibold bg-green-700 text-white h-8 w-8 rounded"
                            >
                                {{ review.rating }}★
                            </div>
                            <p class="ml-4 font-semibold">{{ review.title }}</p>
                        </div>
                    </div>
                    <div class="col-span-2 mt-1">
                        <div class="flex items-center">
                            <p class="text-gray-700 font-semibold text-sm">
                                {{ review.user.name }}
                            </p>
                            <p
                                id="review-8c15d572-bf25-4180-b880-88696043528f"
                                class="text-gray-500 text-sm"
                            >
                                <span>, {{ review.user.address.city }}</span>
                            </p>
                            <p class="text-gray-700 ml-auto font-bold text-sm">
                                {{ formatDate(review.updated_at) }}
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>
</template>
<style scoped>
.product-details {
    display: grid;
    grid-template-columns: 1fr;
    grid-template-rows: auto auto;
    grid-template-areas: "image" "details";
}
@media (min-width: 768px) {
    .product-details {
        display: grid;
        grid-template-columns: 1fr 1fr;
        grid-template-rows: auto;
        grid-template-areas: "image details";
    }
}
.heart-button {
    color: #000; /* Default color */
}
.heart-button.added {
    color: red; /* Color when added to wishlist */
}
</style>
