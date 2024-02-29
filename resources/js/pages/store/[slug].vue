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

// const toggleWishlist = () => {
//     if (addedToWishlist.value) {
//         removeFromWishlist();
//     } else {
//         addToWishlist();
//     }
// };

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
    checkWishlist();
    // const response = await axios.get(`/api/wishlists/${userId}`);
    // wishlists.value = response.data;
    // console.log(response.data)
    // if (
    //     wishlist.value.user_id === userId &&
    //     wishlist.value.product_id === product.value.id
    // ) {
    //     addedToWishlist.value = true;
    // } else {
    //     addedToWishlist.value = false;
    // }
};

const addToWishlist = async () => {
    await fetchUserWishlist();
    // checkWishlist();
    // const response = await axios.get(`/api/wishlists/${userId}`);
    // wishlist.value = response.data;
    // console.log(wishlist.value);
    // if (
    //     wishlist.value.user_id === userId &&
    //     wishlist.value.product_id === product.value.id
    // ) {
    //     addedToWishlist.value = true;
    // } else {
    //     const formData = new FormData();
    //     formData.append("user_id", userId);
    //     formData.append("product_id", product.value.id);
    //     await axios.post("/api/wishlists", formData);
    //     addedToWishlist.value = true;
    // }
    try {
        // Check if the user has already added the product to the wishlist
        // const response = await axios.get(`/api/wishlists/${userId}`);
        // userWishlist.value = response.data;

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
        // console.log(response.data);
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
    <section class="mx-4 my-16 product-details">
        <div class="flex justify-center max-h-96 xl:max-h-[600px]">
            <div class="-m-4 bg-white p-8 rounded-lg w-full">
                <div class="flex items-center justify-between">
                    <v-breadcrumbs class="ml-3 pl-3" :items="breadcrumbItems">
                        <template v-slot:divider>
                            <v-icon icon="mdi-chevron-right"></v-icon>
                        </template>
                    </v-breadcrumbs>
                    <!-- <v-btn
                        style="width: 1px; height: 60px; border-radius: 50%"
                        :class="{
                            'heart-button': true,
                            added: addedToWishlist,
                        }"
                        @click="toggleWishlist"
                    >
                        <v-icon>{{
                            addedToWishlist ? "mdi-heart" : "mdi-heart-outline"
                        }}</v-icon>
                    </v-btn> -->
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
            <!-- <div class="v-container"> -->
            <div class="relative">
                <h1 class="mt-2 text-xl font-bold">{{ product.name }}</h1>
            </div>
            <div class="flex items-center justify-between mt-1">
                <div class="flex items-center">
                    <div class="ml-4">
                        <!-- <div class="text-sm">{{ rating.count }} reviews</div> -->
                    </div>
                </div>
                <!-- <div class="md:mr-4">
          <span class="dark:text-primary-400 text-primary-500 text-xs">{{
            shipping
          }}</span>
        </div> -->
            </div>
            <!-- <div
        v-if="badge || shipping"
        class="flex items-center justify-between mt-4"
      > -->
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
            <!-- <div class="p-4 bg-gray-100 border rounded shadow-md mt-8"> -->
            <!-- border border-gray-300 p-2 mt-6 -->
            <!-- <div class="flex items-center justify-between p-4">
                    <div class="text-xl font-semibold">
                        Ratings &amp; Reviews
                    </div>
                </div>
            </div>
            <div v-for="review in reviews">
                <div class="border border-gray-300 p-6">
                    <div class="row text-lg:md">
                        <span class="color-green">{{ review.rating }}★</span>&nbsp;&nbsp;&nbsp;
                        <span>{{ review.title }}</span>
                    </div>
                    <div class="text-gray-600 text-sm:md mt-4">
                        <div>{{ review.description }}</div>
                        <div>~ {{ review.user.name }}</div>
                    </div>
                </div>
            </div> -->
            <div class="p-4 bg-gray-100 border shadow-md mt-8">
                <!-- border border-gray-300 p-2 mt-6 -->
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
                    <!-- <div class="col-span-2 md:col-span-1">
                    <div class="bg-blue-500 text-white p-2 rounded">
                      <span>Ratings & Reviews</span>
                    </div>
                  </div> -->
                    <!-- <div class="col-span-2 md:col-span-1">
                    <button class="bg-blue-500 text-white px-4 py-2 rounded" @click="rateProduct">
                      <span>Rate Product</span>
                    </button>
                  </div> -->
                    <div class="col-span-2 md:col-span-1 mt-2">
                        <div class="flex items-center">
                            <div
                                class="m-1 p-1 text-lg font-semibold bg-green-700 text-white h-8 w-8 rounded"
                            >
                                {{ review.rating }}★
                            </div>
                            <!-- <img
                                src="data:image/svg+xml;base64,PHN2ZyB4bWxucz0iaHR0cDovL3d3dy53My5vcmcvMjAwMC9zdmciIHdpZHRoPSIxMyIgaGVpZ2h0PSIxMiI+PHBhdGggZmlsbD0iI0ZGRiIgZD0iTTYuNSA5LjQzOWwtMy42NzQgMi4yMy45NC00LjI2LTMuMjEtMi44ODMgNC4yNTQtLjQwNEw2LjUuMTEybDEuNjkgNC4wMSA0LjI1NC40MDQtMy4yMSAyLjg4Mi45NCA0LjI2eiIvPjwvc3ZnPg=="
                                class="w-4 h-4"
                            /> -->
                            <p class="ml-4 font-semibold">{{ review.title }}</p>
                        </div>
                    </div>
                    <!-- <div class="col-span-2 mt-2">
                        <div class="text-sm">
                            {{ review.description }}
                            <span class="text-blue-500 cursor-pointer"
                                ><span>READ MORE</span></span
                            >
                        </div>
                    </div> -->
                    <!-- <div class="col-span-2 md:col-span-1 mt-4">
                        <div class="flex items-center">
                            <div
                                class="w-10 h-10 bg-cover bg-center"
                                style="
                                    background-image: url('https://rukminim1.flixcart.com/blobio/124/124/imr-202105/blobio-imr-202105_c5a1b94f2b4f40e398164db7beee720d.jpg?q=90'),
                                        url('data:image/svg+xml;base64,PHN2ZyB3aWR0aD0iMjIiIGhlaWdodD0iMTgiIHhtbG5zPSJodHRwOi8vd3d3LnczLm9yZy8yMDAwL3N2ZyI+PGcgZmlsbD0iIzlEOUQ5RCIgZmlsbC1ydWxlPSJub256ZXJvIj48cGF0aCBkPSJNMjAgMEgyQzEgMCAwIDEgMCAydjE0YzAgMS4xLjkgMiAyIDJoMThjMSAwIDItMSAyLTJWMmMwLTEtMS0yLTItMnptMCAxNS45MmMtLjAyLjAzLS4wNi4wNi0uMDguMDhIMlYyLjA4TDIuMDggMmgxNy44M2MuMDMuMDIuMDYuMDYuMDguMDh2MTMuODRIMjB6Ii8+PHBhdGggZD0iTTEwIDEyLjUxTDcuNSA5LjUgNCAxNGgxNGwtNC41LTZ6Ii8+PC9nPjwvc3ZnPg==');
                                "
                            ></div>
                        </div>
                    </div> -->
                    <div class="col-span-2 mt-1">
                        <div class="flex items-center">
                            <p class="text-gray-700 font-semibold text-sm">
                                {{ review.user.name }}
                            </p>
                            <!-- <svg
                                width="14"
                                height="14"
                                viewBox="0 0 12 12"
                                xmlns="http://www.w3.org/2000/svg"
                                class="w-4 h-4 text-gray-500 ml-2"
                            >
                                <circle
                                    cx="6"
                                    cy="6"
                                    r="6"
                                    fill="#878787"
                                ></circle>
                                <path
                                    stroke="#FFF"
                                    stroke-width="1.5"
                                    d="M3 6l2 2 4-4"
                                    fill="#878787"
                                ></path>
                            </svg> -->
                            <p
                                id="review-8c15d572-bf25-4180-b880-88696043528f"
                                class="text-gray-500 text-sm"
                            >
                                <!-- <span>Certified Buyer</span> -->
                                <span>, {{ review.user.address.city }}</span>
                            </p>
                            <p class="text-gray-700 ml-auto font-bold text-sm">
                                {{ formatDate(review.updated_at) }}
                            </p>
                            <!-- May, 2021 -->
                        </div>
                    </div>
                    <!-- <div class="col-span-2 mt-4">
                        <div class="flex items-center">
                            <div class="mr-4 flex items-center">
                                <svg
                                    width="20"
                                    height="15"
                                    xmlns="http://www.w3.org/2000/svg"
                                    class="w-4 h-4 text-gray-500"
                                >
                                    <path
                                        fill="#fff"
                                        class="fill-current"
                                        d="M9.58.006c-.41.043-.794.32-1.01.728-.277.557-2.334 4.693-2.74 5.1-.41.407-.944.6-1.544.6v8.572h7.5c.45 0 .835-.28 1.007-.665 0 0 2.207-6.234 2.207-6.834 0-.6-.47-1.072-1.07-1.072h-3.216c-.6 0-1.07-.535-1.07-1.07 0-.537.835-3.387 1.006-3.944.17-.557-.107-1.157-.664-1.35-.15-.043-.257-.086-.407-.064zM0 6.434v8.572h2.143V6.434H0z"
                                        fill-rule="evenodd"
                                    ></path>
                                </svg>
                                <span class="text-gray-500 ml-1">269</span>
                            </div>
                            <div class="flex items-center">
                                <svg
                                    width="20"
                                    height="15"
                                    xmlns="http://www.w3.org/2000/svg"
                                    class="w-4 h-4 text-gray-500"
                                >
                                    <path
                                        fill="#fff"
                                        class="fill-current"
                                        d="M9.58.006c-.41.043-.794.32-1.01.728-.277.557-2.334 4.693-2.74 5.1-.41.407-.944.6-1.544.6v8.572h7.5c.45 0 .835-.28 1.007-.665 0 0 2.207-6.234 2.207-6.834 0-.6-.47-1.072-1.07-1.072h-3.216c-.6 0-1.07-.535-1.07-1.07 0-.537.835-3.387 1.006-3.944.17-.557-.107-1.157-.664-1.35-.15-.043-.257-.086-.407-.064zM0 6.434v8.572h2.143V6.434H0z"
                                        fill-rule="evenodd"
                                    ></path>
                                </svg>
                                <span class="text-gray-500 ml-1">83</span>
                            </div>
                        </div>
                    </div> -->
                    <!-- <div class="col-span-2 mt-4">
                        <div class="flex items-center">
                            <svg
                                width="6"
                                height="11"
                                viewBox="0 0 16 27"
                                xmlns="http://www.w3.org/2000/svg"
                                class="w-4 h-4 text-gray-500"
                            >
                                <path
                                    d="M16 23.207L6.11 13.161 16 3.093 12.955 0 0 13.161l12.955 13.161z"
                                    fill="#fff"
                                    class="fill-current"
                                ></path>
                            </svg>
                            <div class="ml-2">
                                <div class="text-blue-500 cursor-pointer">
                                    <a
                                        href="/reviews/ACCFVFC8BN9YP5HY:33?reviewId=8c15d572-bf25-4180-b880-88696043528f"
                                        ><span>Permalink</span></a
                                    >
                                </div>
                                <div class="text-blue-500 cursor-pointer">
                                    <span>Report Abuse</span>
                                </div>
                            </div>
                        </div>
                    </div> -->
                </div>
            </div>
        </div>
    </section>
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
