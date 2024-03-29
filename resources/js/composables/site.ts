// since `.js, .ts` files are not included by default,
// the following comment tells UnoCSS to force scan this file (to pick the logo icon).
// @unocss-include

export default {
    name: "VuCommerce",
    description: "Vue Designer Nuxt Vuetify Tailwind CSS",
    logo: "i-fluent-emoji:shopping-bags",
    author: "Pinegrow",
    url: "https://pg-nuxt-vuetify-tailwindcss.netlify.app",
    github: "https://github.com/pinegrow/pg-nuxt-vuetify-tailwindcss",
    defaultLocale: "en", // default
    identity: {
        type: "Organization",
    } as any,
    twitter: "@vuedesigner",
    trailingSlash: false, // default
    titleSeparator: "|", // default
    nav: [
        // { text: "Home", link: "/", type: "primary", icon: "i-mdi-home" },
        {
            text: "Electronics",
            link: "/electronics",
            type: "primary",
            icon: "i-mdi-home",
        },
        {
            text: "Computers & Accessories",
            link: "/computer-accessory",
            type: "primary",
            icon: "i-mdi-home",
        },
        {
            text: "Cameras & Photography",
            link: "/camera-photography",
            type: "primary",
            icon: "i-mdi-home",
        },
        {
            text: "Watches",
            link: "/watch",
            type: "primary",
            icon: "i-mdi-home",
        },
        {
            text: "Sports & Fitness",
            link: "/sport-fitness",
            type: "primary",
            icon: "i-mdi-home",
        },
        // {
        //     text: "About Us",
        //     link: "/quick-start",
        //     type: "primary",
        //     icon: "i-mdi-home",
        // },
        {
            text: "My Profile",
            link: "/profile",
            type: "secondary",
            icon: "mdi-account",
        },
        {
            text: "My Wishlists",
            link: "/wishlist",
            type: "secondary",
            icon: "mdi-heart",
        },
        {
            text: "My Orders",
            link: "/orders",
            type: "secondary",
            icon: "mdi-package-variant",
        },
        // {
        //   text: 'Track Order',
        //   link: '/track-order',
        //   type: 'secondary',
        //   icon: 'i-ic-outline-my-location',
        // },
        // {
        //   text: 'Order History',
        //   link: '/order-history',
        //   type: 'secondary',
        //   icon: 'i-ic-baseline-history',
        // },
        // {
        //   text: 'Returns',
        //   link: '/returns',
        //   type: 'secondary',
        //   icon: 'i-material-symbols-assignment-return-outline-rounded',
        // },
        // {
        //   text: 'Delivery Policy',
        //   link: '/delivery-policy',
        //   type: 'secondary',
        //   icon: 'i-tabler-truck-return',
        // },
        // {
        //   text: 'Contact Us',
        //   link: '/contact-us',
        //   type: 'secondary',
        //   icon: 'i-material-symbols-add-call',
        // },
        // {
        //   text: 'Help & FAQs',
        //   link: '/help-faqs',
        //   type: 'secondary',
        //   icon: 'i-material-symbols-contact-support-outline',
        // },
    ],
};
