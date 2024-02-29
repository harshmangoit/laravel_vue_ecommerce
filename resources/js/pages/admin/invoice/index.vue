<template>
    <div class="container">
        <h3 class="text-2xl font-semibold text-center mt-4 mb-6 text-gray-800">
            Invoices<span
                class="inline-block ml-2 px-2 py-1 bg-gray-200 text-gray-800 rounded"
                >{{ invoices.length }}</span
            >
        </h3>

        <v-data-table
            :headers="columns"
            :items="invoices"
            show-select
            :search="search"
            :items-per-page="5"
        >
            <template v-slot:item.id="{ item }">
                <span class="text-blue-500">#{{ item.id }}</span>
            </template>
            <template v-slot:item.created_at="{ item }">
                <span>{{ formatDate(item.created_at) }}</span>
            </template>
            <template v-slot:item.total_amount="{ item }">
                <span>Rs.{{ item.total_amount }}</span>
            </template>
            <template v-slot:item.action="{ item }">
                <v-btn @click="downloadInvoice(item.id)" color="">
                    <v-icon>mdi-download</v-icon>
                </v-btn>
            </template></v-data-table
        >
    </div>
</template>

<script setup>
import jsPDF from "jspdf";
import "jspdf-autotable";
import { ref, onMounted } from "vue";
import { useRouter } from "vue-router";
import axios from "axios";

const router = useRouter();

const columns = [
    { title: "ID", value: "id", sortable: true },
    { title: "INVOICE NO.", value: "invoice_no", sortable: true },
    {
        title: "INVOICE DATE",
        value: "created_at",
        sortable: true,
    },
    { title: "QUANTITY", value: "qty", sortable: true },
    { title: "TOTAL", value: "total_amount", sortable: true },
    { title: "SHIPPING FEE", value: "shipping_fee", sortable: true },
    { title: "INVOICE", value: "action", sortable: false },
];

const search = ref("");

const invoice = ref([]);

async function fetchInvoice(id) {
    try {
        const response = await axios.get(`/api/invoices/${id}`);
        invoice.value = response.data;
        // orderItems.value = response.data.order_items;
    } catch (error) {
        console.error("Error fetching product data:", error);
    }
}

function createInvoicePdf(invoice) {
    try {
        console.log(invoice);
        // console.log(
        //     invoice.order.order_items.map((item) => [
        //         item.product.name,
        //         item.product.price,
        //         item.quantity,
        //         item.price,
        //     ])
        // );
        // Create a new instance of jsPDF
        const pdf = new jsPDF();

        // Add text to the PDF using the provided data
        pdf.text(`INVOICE:`, 10, 10);

        // Add invoice number and date
        pdf.text(`Invoice Number: ${invoice.invoice_no}`, 10, 20);
        pdf.text(`Date: ${formatDate(invoice.created_at)}`, 10, 30);

        // Add Order and Account Information
        pdf.text(
            `Order ID: ${invoice.order.id}\nOrder Date: ${formatDate(
                invoice.order.created_at
            )}\nOrder Status: ${invoice.order.status}`,
            10,
            50
        );

        // Add Company Information
        pdf.text("Company:", 10, 80);
        pdf.text(
            "Mango IT Solutions\n15/3, Near Mehta Marketing, Old Palasia\nIndore, MP\nIndia, 452001",
            10,
            90
        );

        // Add Client Information
        pdf.text("Client:", 10, 120);
        pdf.text(
            `Name: ${invoice.order_address.name}\nAddress: ${invoice.order_address.country}\nPost code: ${invoice.order_address.postcode}\nContact: ${invoice.order_address.contact}\n\nShipping Method: ${invoice.order.payment_method}`,
            10,
            130
        );

        // Add Ship To Information
        pdf.text("Ship To:", 10, 170);
        pdf.text(
            `Name: ${invoice.order_address.name}\nAddress: ${invoice.order_address.country}\nPost code: ${invoice.order_address.postcode}\nContact: ${invoice.order_address.contact}`,
            10,
            180
        );

        // Add Table with Product Details
        const startY = 210; // Adjust the starting Y-coordinate as needed
        const columnWidths = [70, 40, 40, 40]; // Adjust column widths as needed
        const rowHeight = 10;
        const tableHeaders = ["Product Name", "Price", "Qty", "Total Price"];

        pdf.autoTable({
            startY,
            head: [tableHeaders],
            body: invoice.order.order_items.map((item) => [
                item.product.name,
                item.product.price,
                item.quantity,
                item.price,
            ]),
            columnStyles: {
                0: { cellWidth: columnWidths[0] },
                1: { cellWidth: columnWidths[1] },
                2: { cellWidth: columnWidths[2] },
                3: { cellWidth: columnWidths[3] },
            },
        });

        // Calculate Grand Total
        // const grandTotal = invoice.order.order_items.reduce(
        //     (total, item) => 0 + item.price,
        //     0
        // );

        pdf.text(
            `Grand Total: ${invoice.total_amount}`,
            10,
            pdf.autoTable.previous.finalY + 20
        );

        // Save the PDF content to a variable
        const pdfContent = pdf.output();
        return pdfContent;
    } catch (error) {
        console.error("Error creating PDF:", error);
        throw error; // Rethrow the error to propagate it
    }
}

async function downloadInvoice(id) {
    await fetchInvoice(id);

    const pdfContent = createInvoicePdf(invoice.value);

    const blob = new Blob([pdfContent], { type: "application/pdf" });

    const link = document.createElement("a");
    link.href = window.URL.createObjectURL(blob);
    link.download = "Invoice.pdf";
    link.click();

    // Update the order status to 'completed'
    // await axios.put(
    //     `http://localhost:8000/api/orderupdate/${orders.value.id}`,
    //     {
    //         status: "completed",
    //     }
    // );
    // router.push({ name: "admin-invoice-id", params: { id } });
}

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

const invoices = ref([]);

const fetchInvoices = async () => {
    try {
        const response = await axios.get("/api/invoices");
        invoices.value = response.data;
    } catch (error) {
        console.error("Error fetching invoices:", error);
    }
};

onMounted(fetchInvoices);
</script>

<style></style>

<route lang="yaml">
meta:
    requiresAuth: true
    layout: AdminLayout
</route>
