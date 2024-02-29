<template>
    <div class="content container-fluid">
        <!-- Page Header -->
        <div class="page-header d-print-none">
            <div class="row align-items-center">
                <div class="col-sm mt-8 ml-6 mb-4 mb-sm-0">
                    <ol class="flex items-center space-x-2 text-gray-500">
                        <li>
                            <RouterLink
                                class="hover:text-gray-700"
                                to="/admin/invoice"
                                >Invoices</RouterLink
                            >
                            /
                        </li>
                        <li class="text-gray-900">
                            <span>Invoice Detail</span>
                        </li>
                    </ol>

                    <div class="d-sm-flex align-items-sm-center flex">
                        <div>
                            <h1 class="text-2xl font-semibold mb-4">
                                Invoice No. #{{ invoice.invoice_no }}
                            </h1>
                            <span
                                v-if="invoice.order == 'succeeded'"
                                class="inline-block px-2 py-2 text-xs font-semibold rounded bg-green-100 text-green-400"
                            >
                                <span
                                    class="inline-block w-3 h-3 mr-1 rounded-full bg-green-400"
                                ></span
                                >Paid
                            </span>
                            <span
                                v-else-if="invoice.order == 'pending'"
                                class="inline-block px-2 py-2 text-xs font-semibold rounded bg-yellow-50 text-yellow-400"
                            >
                                <span
                                    class="inline-block w-3 h-3 mr-1 rounded-full bg-yellow-400"
                                ></span
                                >Pending
                            </span>
                            <span
                                v-else-if="invoice.order == 'processing'"
                                class="inline-block px-2 py-2 text-xs font-semibold rounded bg-orange-50 text-orange-400"
                            >
                                <span
                                    class="inline-block w-3 h-3 mr-1 rounded-full bg-orange-400"
                                ></span
                                >Processing
                            </span>
                            <span class="ms-2 ms-sm-3">
                                <i class="bi-calendar-week"></i>
                                {{ formatDate(invoice.created_at) }}
                            </span>
                        </div>
                        <div style="margin-left: 46%">
                            <button
                                v-if="
                                    order.status == 'succeeded' ||
                                    order.status == 'pending'
                                "
                                class="bg-blue-500 text-white hover:bg-blue-600 py-2 px-4 rounded-md mr-2"
                                @click="generateInvoice"
                            >
                                Generate Invoice
                            </button>
                            <!-- <button
                                class="bg-gray-500 text-white hover:bg-gray-600 py-2 px-4 rounded-md"
                                @click="generateShipment"
                            >
                                Shipment
                            </button> -->
                        </div>
                    </div>
                </div>
                <!-- End Col -->
            </div>
            <br />
            <!-- End Row -->
            <hr />

            <div class="mt-6 ml-6 flex">
                <!-- Left Column -->
                <div class="flex-grow">
                    <!-- Order Details Card -->
                    <div class="card mb-5">
                        <!-- Header -->
                        <div class="card-header card-header-content-between">
                            <h4 class="inline text-xl font-semibold mb-2">
                                Order details
                            </h4>
                            <!-- <h4 class="card-header-title">
                                Order details -->
                            <!-- <span
                                    class="badge bg-soft-dark text-dark rounded-full ms-1"
                                    >4</span
                                > -->
                            <!-- </h4> -->
                        </div>
                        <!-- End Header -->

                        <!-- Body -->
                        <div class="mt-4 card-body">
                            <!-- Media -->
                            <!-- Repeat this section for each product -->
                            <div
                                v-for="(order, index) in orderItems"
                                :key="index"
                                class="flex border-t border-gray-300"
                            >
                                <!-- <div class="flex"> -->
                                <div class="flex-shrink-0 w-20 mt-2 mb-2">
                                    <!-- <div class="avatar avatar-xl"> -->
                                    <img
                                        class="w-full h-15 object-cover"
                                        :src="order.product.image_path"
                                        alt="Product Image"
                                    />
                                    <!-- </div> -->
                                </div>

                                <div class="flex-grow-1 ms-3 grid grid-cols-5 gap-x-4 mt-6">
                                    <!-- Product Name -->
                                    <div class="col-span-2">
                                        <a class="h5 d-block" :href="order.detailsLink">{{ order.product.name }}</a>
                                    </div>
                                
                                    <!-- Product Price -->
                                    <div class="col-span-1 text-center">
                                        <h5>Rs. {{ order.product.price }}</h5>
                                    </div>
                                
                                    <!-- Product Quantity -->
                                    <div class="col-span-1 text-center">
                                        <h5>{{ order.quantity }}</h5>
                                    </div>
                                
                                    <!-- Total Price -->
                                    <div class="col-span-1 text-center">
                                        <h5>Rs. {{ order.price }}</h5>
                                    </div>
                                </div>
                                
                                
                                <hr />
                            </div>
                            <!-- End Media -->

                            <!-- Order Summary -->
                            <div class="flex justify-end mt-10">
                                <div class="w-full sm:w-2/3 lg:w-7/12">
                                    <h4 class="text-xl font-bold mb-4">
                                        Order Summary:
                                    </h4>
                                    <dl class="grid grid-cols-6 gap-x-4 text-l">
                                        <dt
                                            class="col-span-3 sm:col-span-2 font-bold"
                                        >
                                            Subtotal:
                                        </dt>
                                        <dd class="col-span-3 sm:col-span-4">
                                            Rs. {{ order.total_amount }}
                                        </dd>

                                        <dt
                                            class="col-span-3 sm:col-span-2 font-bold"
                                        >
                                            Shipping fee:
                                        </dt>
                                        <dd class="col-span-3 sm:col-span-4">
                                            Free
                                        </dd>

                                        <dt
                                            class="col-span-3 sm:col-span-2 font-bold"
                                        >
                                            Tax:
                                        </dt>
                                        <dd class="col-span-3 sm:col-span-4">
                                            Rs. 0.00
                                        </dd>

                                        <dt
                                            class="col-span-3 sm:col-span-2 font-bold"
                                        >
                                            Total:
                                        </dt>
                                        <dd class="col-span-3 sm:col-span-4">
                                            Rs. {{ order.total_amount }}
                                        </dd>

                                        <dt
                                            class="col-span-3 sm:col-span-2 font-bold"
                                        >
                                            Amount paid:
                                        </dt>
                                        <dd
                                            v-if="
                                                order.payment_method === 'cod'
                                            "
                                            class="col-span-3 sm:col-span-4"
                                        >
                                            Rs. 0.00
                                        </dd>
                                        <dd
                                            v-else
                                            class="col-span-3 sm:col-span-4"
                                        >
                                            Rs. {{ order.total_amount }}
                                        </dd>
                                    </dl>
                                </div>
                            </div>
                            <!-- End Order Summary -->
                        </div>
                        <!-- End Body -->
                    </div>
                    <!-- End Order Details Card -->
                </div>

                <!-- Right Column -->
                <div class="flex-shrink-0 w-1/3">
                    <!-- Customer Card -->
                    <div class="card">
                        <!-- Header -->
                        <div class="card-header">
                            <h4 class="text-xl font-semibold mb-2">Customer</h4>
                        </div>
                        <!-- End Header -->

                        <!-- Body -->
                        <div class="card-body">
                            <!-- Customer Info -->
                            <ul
                                class="list-group list-group-flush list-group-no-gutters"
                            >
                                <li class="list-group-item">
                                    <a class="d-flex align-items-center">
                                        <!-- :href="customer.detailsLink" -->
                                        <div class="avatar avatar-circle">
                                            <!-- <img
                                            :src="customer.avatar"
                                            class="avatar-img"
                                            alt="Customer Image"
                                            /> -->
                                        </div>
                                        <div class="flex-grow-1 ms-3">
                                            <span
                                                class="text-body text-inherit"
                                                >{{ order.address?.name }}</span
                                            >
                                        </div>
                                        <div class="flex-grow-1 text-end">
                                            <i
                                                class="bi-chevron-right text-body"
                                            ></i>
                                        </div>
                                    </a>
                                </li>
                                <hr />
                                <!-- <li class="mt-2 list-group-item">
                                    <a
                                        class="d-flex align-items-center"
                                        :href="customer.ordersLink"
                                    >
                                        <div
                                            class="icon icon-soft-info icon-circle"
                                        >
                                            <i class="bi-basket"></i>
                                        </div>
                                        <div class="flex-grow-1 ms-3">
                                            <span class="text-body text-inherit"
                                                >{{
                                                    customer.orderCount
                                                }}
                                                orders</span
                                            >
                                        </div>
                                        <div class="flex-grow-1 text-end">
                                            <i
                                                class="bi-chevron-right text-body"
                                            ></i>
                                        </div>
                                    </a>
                                </li> -->

                                <li class="mt-2 list-group-item">
                                    <div
                                        class="d-flex justify-content-between align-items-center"
                                    >
                                        <h5 class="text-xl font-semibold mb-2">
                                            Contact info
                                        </h5>
                                    </div>

                                    <ul
                                        class="list-unstyled list-py-2 text-body"
                                    >
                                        <li>
                                            <v-icon>mdi-email</v-icon>&nbsp;
                                            {{ order.address?.email }}
                                        </li>
                                        <li>
                                            <v-icon>mdi-phone</v-icon>&nbsp;
                                            {{ order.address?.contact }}
                                        </li>
                                    </ul>
                                </li>
                                <hr />
                                <li class="mt-4 list-group-item">
                                    <div
                                        class="d-flex justify-content-between align-items-center"
                                    >
                                        <h5 class="text-xl font-semibold mb-2">
                                            Shipping address:
                                        </h5>
                                    </div>

                                    <span class="d-block text-body">
                                        {{ order.address?.address }},<br />
                                        {{ order.address?.city }}<br />
                                        {{ order.address?.state }} - ({{
                                            order.address?.postcode
                                        }})<br />
                                        <span
                                            >{{ order.address?.country }}
                                            <img
                                                src="data:image/png;base64,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"
                                                class="inline-block w-6 h-6 me-2"
                                                alt="Country Flag"
                                        /></span>
                                    </span>
                                </li>
                                <hr />
                                <li class="mt-4 list-group-item">
                                    <div
                                        class="d-flex justify-content-between align-items-center"
                                    >
                                        <h5 class="text-xl font-semibold mb-2">
                                            Billing address:
                                        </h5>
                                    </div>

                                    <span class="d-block text-body">
                                        {{ order.address?.address }},<br />
                                        {{ order.address?.city }}<br />
                                        {{ order.address?.state }} - ({{
                                            order.address?.postcode
                                        }})<br />
                                        {{ order.address?.country }}
                                        <img
                                            src="data:image/png;base64,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"
                                            class="inline-block w-6 h-6 me-2"
                                            alt="Country Flag"
                                        />
                                    </span>
                                    <hr />
                                    <div class="mt-3">
                                        <h5 class="text-xl font-semibold mb-2">
                                            Payment Method:
                                        </h5>
                                        <div
                                            v-if="
                                                order.payment_method == 'card'
                                            "
                                        >
                                            <div class="flex items-center">
                                                <span class="text-s font-bold"
                                                    >Card:</span
                                                >
                                                <img
                                                    class="w-8 h-6 me-2"
                                                    src="data:image/png;base64,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"
                                                    alt="Image Description"
                                                />
                                                <span class="text-dark"
                                                    >•••• 4242</span
                                                >
                                            </div>
                                        </div>
                                        <div v-else>
                                            <div class="flex items-center">
                                                <img
                                                    class="w-15 h-8 me-2"
                                                    src="https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcTIFJCtizKWMa-e2BuQd9QsUCTsYyXdYcod6w&usqp=CAU"
                                                    alt="Image Description"
                                                />
                                                <span class="text-dark"
                                                    >Cash</span
                                                >
                                            </div>
                                        </div>
                                        <!-- <h5 class="mb-0">
                                            {{ order.payment_method }}
                                        </h5>
                                        <span class="d-block text-body"
                                            >Card Number: ************4242</span
                                        > -->
                                    </div>
                                </li>
                            </ul>
                            <!-- End Customer Info -->
                        </div>
                        <!-- End Body -->
                    </div>
                    <!-- End Customer Card -->
                </div>
            </div>
        </div>
        <!-- End Page Header -->
        <div class="mt-20"></div>
        <!-- ... (rest of your code) ... -->
    </div>
</template>

<script setup>
import { ref, onMounted } from "vue";
import { useRoute, useRouter } from "vue-router";
import axios from "axios";

const route = useRoute();
const router = useRouter();
const invoiceId = route.params.id;

const invoice = ref([]);
const orderItems = ref([]);

const order = ref([]);
function formatDate(dateTimeString) {
    const options = {
        month: "short",
        day: "numeric",
        year: "numeric",
        hour: "numeric",
        minute: "numeric",
        second: "numeric",
        hour12: true,
    };

    const date = new Date(dateTimeString);
    return date.toLocaleString("en-US", options);
}

// async function generateInvoice() {
//     const randomNo = Math.floor(Math.random() * 10000000) + 1;
//     const invoiceNo = `INV${randomNo}`;
//     const quantity = orderItems.value.reduce(
//         (sum, item) => sum + item.quantity,
//         0
//     );
//     const formData = new FormData();
//     formData.append("invoice_no", invoiceNo);
//     formData.append("order_id", invoiceId);
//     formData.append("order_address_id", order.value.address.id); // Use orderAddress.id
//     formData.append("qty", quantity);
//     formData.append("total_amount", order.value.total_amount);
//     formData.append("shipping_fee", "0.00");

//     try {
//         // Generate invoice
//         const response = await axios.post(
//             "http://localhost:8000/api/invoices",
//             formData
//         );
//         console.log(response.data);

//         // Update order status to 'processing'
//         await axios.put(`http://localhost:8000/api/orders/${invoiceId}`, {
//             status: "processing",
//         });

//         router.push({ path: "/admin/order" });
//     } catch {
//         console.error(
//             "Error generating invoice or updating order status:",
//             error
//         );
//     }
// }

const fetchOrderById = async () => {
    try {
        const response = await axios.get(`/api/invoices/${invoiceId}`);
        console.log(response.data)
        invoice.value = response.data;
        orderItems.value = response.data.order_items;
    } catch (error) {
        console.error("Error fetching product data:", error);
    }
};

onMounted(fetchOrderById);
</script>

<style lang="scss" scoped></style>

<route lang="yaml">
meta:
    requiresAuth: true
    layout: AdminLayout
</route>
