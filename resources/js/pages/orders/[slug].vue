<template>
    <div class="v-container">
        <h1 class="text-2xl font-semibold text-center">Order Detail</h1>
        <!-- <div class="flex items-center justify-between p-4 bg-gray-100">
            <div class="flex items-center space-x-2">
                <a href="/" class="text-blue-500">Home</a>
                <svg
                    width="16"
                    height="27"
                    viewBox="0 0 16 27"
                    xmlns="http://www.w3.org/2000/svg"
                    class="text-blue-500"
                >
                    <path
                        d="M16 23.207L6.11 13.161 16 3.093 12.955 0 0 13.161l12.955 13.161z"
                        fill="#fff"
                    ></path>
                </svg>
                <a href="/account?link=my_account" class="text-blue-500"
                    >My Account</a
                >
                <svg
                    width="16"
                    height="27"
                    viewBox="0 0 16 27"
                    xmlns="http://www.w3.org/2000/svg"
                    class="text-blue-500"
                >
                    <path
                        d="M16 23.207L6.11 13.161 16 3.093 12.955 0 0 13.161l12.955 13.161z"
                        fill="#fff"
                    ></path>
                </svg>
                <a href="/account/orders?link=home_orders" class="text-blue-500"
                    >My Orders</a
                >
                <svg
                    width="16"
                    height="27"
                    viewBox="0 0 16 27"
                    xmlns="http://www.w3.org/2000/svg"
                    class="text-blue-500"
                >
                    <path
                        d="M16 23.207L6.11 13.161 16 3.093 12.955 0 0 13.161l12.955 13.161z"
                        fill="#fff"
                    ></path>
                </svg>
                <div class="flex items-center">
                    <div class="text-sm font-semibold">
                        OD219987969953008000
                    </div>
                    <svg
                        width="16"
                        height="27"
                        viewBox="0 0 16 27"
                        xmlns="http://www.w3.org/2000/svg"
                        class="text-blue-500"
                    >
                        <path
                            d="M16 23.207L6.11 13.161 16 3.093 12.955 0 0 13.161l12.955 13.161z"
                            fill="#fff"
                        ></path>
                    </svg>
                </div>
            </div>
            <div class="flex space-x-4">
                <div class="flex items-center">
                    <span class="text-blue-500 cursor-pointer">
                        <img
                            src="//static-assets-web.flixcart.com/fk-p-linchpin-web/fk-cp-zion/img/downloadInvoice_e0f744.png"
                            class="w-6 h-6"
                            alt="Download Invoice"
                        />
                        <span class="ml-1">Download Invoice</span>
                    </span>
                </div>
            </div>
        </div> -->

        <div class="flex p-4">
            <div class="col-span-7/12">
                <div class="p-10 bg-white shadow-md pr-72">
                    <div class="text-lg font-semibold">Delivery Address</div>
                    <div class="mt-4">
                        <div class="font-semibold">{{ orderAddress.name }}</div>
                        <div class="mt-2">{{ orderAddress.email }}</div>
                        <div>
                            {{ orderAddress.address }}, {{ orderAddress.city }} -
                            {{ orderAddress.postcode }}, {{ orderAddress.state }}
                        </div>
                        <div class="mt-2">
                            <span class="font-semibold">Phone number </span>&nbsp;&nbsp;&nbsp;<span>{{ orderAddress.contact }}</span>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-span-5/12 p-4">
                <div class="flex items-center">
                    
                    <div class="font-semibold text-blue-500 cursor-pointer">
                        More actions
                    </div>
                    <div class="flex items-center ml-4">
                        <!-- <img
                            src="//static-assets-web.flixcart.com/fk-p-linchpin-web/fk-cp-zion/img/downloadInvoice_e0f744.png"
                            class="w-6 h-6"
                            alt="Download Invoice"
                        /> -->
                        <span class="ml-1">Download Invoice</span>
                        <button
                            class="bg-blue-500 text-white px-4 py-2 rounded"
                        >
                            <span>Download</span>
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <!-- <div class="flex p-4">
            <div class="col-span-3/12">
                <div class="flex items-center">
                    <div
                        class="w-16 h-16 bg-cover bg-center"
                        style="
                            background-image: url('https://rukminim2.flixcart.com/image/xif0q/headphone/m/a/5/-original-imagg33vvpyve8uq.jpeg');
                        "
                    ></div>
                    <div class="ml-4">
                        <a
                            href="/boult-curve-boomx-rich-bass-flexi-band-magnetic-earbuds-ipx5-water-resistant-bluetooth-headset/p/itmbfd57eac5bfa9?pid=ACCFEZ99MFMDR8CM&amp;lid=LSTACCFEZ99MFMDR8CMAJFROP"
                            target="_blank"
                            class="text-blue-500"
                            >Boult Audio ProBass Curve Neckband Bluet...</a
                        >
                        <div class="text-gray-500">Color: Black,Grey</div>
                        <div class="text-gray-500">Seller: SuperComNet</div>
                        <div class="font-semibold text-blue-500">₹899</div>
                    </div>
                </div>
            </div>
            <div class="col-span-5/12">
                <div class="flex items-center space-x-4">
                    <div class="flex items-center">
                        <div
                            class="flex-shrink-0 w-2 h-2 rounded-full bg-green-500"
                        ></div>
                        <div class="ml-2">Order Confirmed</div>
                    </div>
                    <div class="flex items-center">
                        <div
                            class="flex-shrink-0 w-2 h-2 rounded-full bg-green-500"
                        ></div>
                        <div class="ml-2">Shipped</div>
                    </div>
                    <div class="flex items-center">
                        <div
                            class="flex-shrink-0 w-2 h-2 rounded-full bg-green-500"
                        ></div>
                        <div class="ml-2">Out for delivery</div>
                    </div> -->
                    <!-- <div class="flex items-center">
                        <div
                            class="flex-shrink-0 w-2 h-2 rounded-full bg-green-500"
                        ></div>
                        <div class="ml-2">Delivered</div>
                    </div> -->
                <!-- </div> -->
                <!-- <div class="flex items-center space-x-4 mt-2">
                    <div>
                        <div class="font-semibold">Refund completed</div>
                    </div>
                </div> -->
            <!-- </div>
        </div>

        <div class="flex p-4">
            <div class="flex items-center">
                <div class="text-gray-500">Other items in this order</div>
                <a
                    href="/order_details?order_id=OD219987969953008000&amp;item_id=21998796995300801&amp;unit_id=21998796995300801000"
                    class="ml-2 text-blue-500"
                    >View Details</a
                >
            </div>
            <div class="grid grid-cols-1 gap-4 mt-2">
                <div class="bg-white p-4 shadow-md">
                    <div class="flex items-center">
                        <div
                            class="w-16 h-16 bg-cover bg-center"
                            style="
                                background-image: url('https://rukminim2.flixcart.com/image/kar44280/headphone/2/s/i/bassheads-225-boat-original-imafs8hx9a4b8tuh.jpeg');
                            "
                        ></div>
                        <div class="ml-4">
                            <a
                                href="/boat-bassheads-225-wired-headset/p/itm77e99bc4a8b6d?pid=ACCFS8HZZ9VKHQXF&amp;lid=LSTACCFS8HZZ9VKHQXFLDSSTI"
                                target="_blank"
                                class="text-blue-500"
                                >boAt BassHeads 225 Wired Headset</a
                            >
                            <div class="text-gray-500">Color: Black</div>
                            <div class="text-gray-500">Seller: HIYAN TEX</div>
                            <div class="font-semibold">₹369</div>
                        </div>
                    </div>
                    <div class="flex items-center space-x-4 mt-2">
                        <div>
                            <div
                                class="flex-shrink-0 w-2 h-2 rounded-full bg-green-500"
                            ></div>
                            <div class="ml-2">Refund completed</div>
                        </div>
                    </div>
                </div>
                <div class="bg-white p-4 shadow-md">
                    <div class="flex items-center">
                        <div
                            class="w-16 h-16 bg-cover bg-center"
                            style="
                                background-image: url('https://rukminim2.flixcart.com/image/ksnjp8w0/headphone/e/d/l/rockerz-255f-rockerz-255-boat-original-imag66ckxprfrsz2.jpeg');
                            "
                        ></div>
                        <div class="ml-4">
                            <a
                                href="/boat-rockerz-255f-bluetooth-headset/p/itmd35e57aca99fb?pid=ACCF6SZ8EFWFEPZ6&amp;lid=LSTACCF6SZ8EFWFEPZ6NDXHEI"
                                target="_blank"
                                class="text-blue-500"
                                >boAt Rockerz 255F Bluetooth Headset</a
                            >
                            <div class="text-gray-500">Color: Black</div>
                            <div class="text-gray-500">Seller: CORSECA</div>
                            <div class="font-semibold">₹899</div>
                        </div>
                    </div>
                    <div class="flex items-center space-x-4 mt-2">
                        <div>
                            <div
                                class="flex-shrink-0 w-2 h-2 rounded-full bg-green-500"
                            ></div>
                            <div class="ml-2">Delivered on Oct 24, 2020</div>
                            <div class="ml-2">Your item has been delivered</div>
                        </div>
                    </div>
                </div>
            </div>
        </div> -->
    </div>
</template>

<script setup>
import axios from 'axios';
import { ref, onMounted } from 'vue';
import { useRoute } from "vue-router";
const route = useRoute();
const order = ref([])
const orderAddress = ref([])

const fetchOrderById = async () => {
    try {
        const response = await axios.get(`/api/orders/${route.params.slug}`);
        // console.log(response.data);
        order.value = response.data;
        orderAddress.value = response.data.address;
        // orderItems.value = response.data.order_items;
    } catch (error) {
        console.error("Error fetching product data:", error);
    }
};

onMounted(fetchOrderById);
</script>

<style lang="scss" scoped></style>
