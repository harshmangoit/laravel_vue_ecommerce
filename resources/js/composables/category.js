import axios from "axios";

export const useCategory = async (category) => {
    const { data: productCategory, error } = await axios.get(
        `http://127.0.0.1:8000/api/category/${category}`
    );
    if (error.value) {
        throw createError({
            ...error.value,
            statusMessage: `Couldn't fetch product url ${category}.`,
        });
    }

    const fetchProductsByCategory = () => {
        console.log(productCategory.value.products)
        return productCategory.value.products;
    };

    return { productCategory, fetchProductsByCategory };
};
