export const useFavoriteProductsState = (initialValue = []) => {
    let favoriteProducts = initialValue && Array.isArray(initialValue) ? initialValue : [];
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

