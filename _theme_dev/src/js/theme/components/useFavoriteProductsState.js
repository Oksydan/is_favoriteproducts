let favoriteProducts = [];

export const useFavoriteProductsState = (initialValue = []) => {
    if (initialValue && Array.isArray(initialValue) && initialValue.length > 0) {
        favoriteProducts = initialValue;
    }

    const getFavoriteProducts = () => favoriteProducts;

    const setFavoriteProducts = (products) => {
        favoriteProducts = products;
    };

    const addProductKey = (key) => {
        const currentFavoriteProducts = getFavoriteProducts();

        setFavoriteProducts([...currentFavoriteProducts, key]);
    };

    const removeProductKey = (key) => {
        const currentFavoriteProducts = getFavoriteProducts();

        setFavoriteProducts(currentFavoriteProducts.filter((productKey) => productKey !== key));
    };

    return {
        getFavoriteProducts,
        addProductKey,
        removeProductKey,
    };
};

