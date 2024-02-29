<template>
    <div class="v-container">
        <div class="flex justify-between kbhDeL pr-4 border-b">
            <div class="KpPZbF text-2xl font-semibold mb-4">
                <h1>Ratings & Reviews</h1>
            </div>
            <div class="_52cNDb">
                <router-link :to="`/store/${product.url_slug}`">
                    <div class="_2snLhA flex items-center">
                        <div class="_1k8CFo flex-1">
                            <h6 class="text-xl text-gray-800">
                                {{ product.name }}
                            </h6>
                        </div>

                        <div class="_2jTZAi ml-4">
                            <img
                                :src="product.image_path"
                                class="_4BdIGR rounded-full h-20 w-20"
                            />
                        </div>
                    </div>
                </router-link>
            </div>
        </div>
        <div class="p-8 space-y-4">
            <!-- Rate Section -->
            <div class="border-b pb-4">
                <div class="text-lg font-semibold mb-2">Rate this product</div>
                <div class="flex items-center">
                    <!-- <div class="mr-2">
                        <span
                            id="star-0-add-rating"
                            class="text-2xl cursor-pointer"
                            >★</span
                        >
                        <span
                            id="star-1-add-rating"
                            class="text-2xl cursor-pointer"
                            >★</span
                        >
                        <span
                            id="star-2-add-rating"
                            class="text-2xl cursor-pointer"
                            >★</span
                        >
                        <span
                            id="star-3-add-rating"
                            class="text-2xl cursor-pointer"
                            >★</span
                        >
                        <span
                            id="star-4-add-rating"
                            class="text-2xl cursor-pointer"
                            >★</span
                        >
                    </div> -->
                    <!-- <span class="text-gray-600">Very Bad</span> -->
                    <div v-for="star in stars" :key="star.id" class="mr-2">
                        <span
                            :id="'star-' + star.id + '-add-rating'"
                            class="text-3xl cursor-pointer"
                            @click="rateProduct(star.id)"
                            :class="{
                                'text-yellow-500': star.id <= selectedRating,
                            }"
                        >
                            ★
                        </span>
                    </div>
                    <span class="text-gray-600 font-semibold">{{
                        selectedRatingLabel
                    }}</span>
                </div>
                <!-- <div class="mt-2 text-sm text-gray-500">Your rating has been saved</div> -->
            </div>

            <!-- Review Section -->
            <div>
                <div class="text-lg font-semibold mb-2">
                    Review this product
                </div>
                <div>
                    <div class="mb-4">
                        <div class="text-sm text-gray-600 mb-1">
                            Description
                        </div>
                        <textarea
                            rows="8"
                            placeholder="Description..."
                            class="w-full border p-2"
                            v-model="description"
                        ></textarea>
                    </div>
                    <div class="mb-4">
                        <div class="text-sm text-gray-600 mb-1">
                            Title (optional)
                        </div>
                        <input
                            placeholder="Review title..."
                            class="w-full border p-2"
                            v-model="title"
                        />
                    </div>
                </div>
            </div>

            <!-- Image Upload Section -->
            <!-- <div class="mb-4">
                <label for="fk-rnuw4d2ssa" class="text-sm text-gray-600"
                    >Upload Images</label
                >
                <div class="flex items-center mt-2">
                    <input
                        class="hidden"
                        id="fk-rnuw4d2ssa"
                        type="file"
                        accept="image/*"
                        multiple
                    />
                    <label
                        for="fk-rnuw4d2ssa"
                        class="px-4 py-2 bg-blue-500 text-white cursor-pointer"
                        >Browse</label
                    >
                </div>
            </div> -->

            <!-- Submit Button -->
            <button
                class="px-6 py-2 bg-green-500 rounded text-white"
                @click="submitReview"
            >
                Submit
            </button>
        </div>
    </div>
</template>

<script setup>
import { ref, computed, onMounted } from "vue";
import { useRoute, useRouter } from "vue-router";
import axios from "axios";
import { useAuthStore } from "../../stores/auth";

const route = useRoute();
const router = useRouter();
const authStore = useAuthStore();
const title = ref("");
const description = ref("");
const product = ref("");
const selectedRating = ref(5);

const stars = Array.from({ length: 5 }, (_, index) => ({ id: index + 1 }));

const rateProduct = (rating) => {
    selectedRating.value = rating;
};

const selectedRatingLabel = computed(() => {
    switch (selectedRating.value) {
        case 1:
            return "Very Bad";
        case 2:
            return "Bad";
        case 3:
            return "Okay";
        case 4:
            return "Good";
        case 5:
            return "Excellent";
        default:
            return "";
    }
});

const submitReview = async () => {
    if (description.value !== "") {
    const formData = new FormData();
    formData.append("product_id", product.value.id);
    formData.append("user_id", authStore.userData.id);
    formData.append("rating", selectedRating.value);
    formData.append("title", title.value);
    formData.append("description", description.value);
        try {
            // Generate invoice
            const response = await axios.post("/api/reviews", formData);
            console.log(response.data);

            router.push({ path: "/orders" });
        } catch {
            console.error(
                "Error updating rating & reviews:",
                error
            );
        }
    }
};

const fetchProductById = async () => {
    try {
        const response = await axios.get(`/api/products/${route.params.slug}`);
        product.value = response.data.product;
    } catch (error) {
        console.error("Error fetching product data:", error);
    }
};

onMounted(()=>{
    fetchProductById()
});
</script>

<style scoped>
/* Add any additional styles or overrides here */
</style>
