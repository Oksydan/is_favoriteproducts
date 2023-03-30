import { useFavoriteProductsState } from './useFavoriteProductsState';

export const useFavoriteProducts = () => {
    // favoriteProducts is a global variable set via Media::addJsDef
    const initialState = window.favoriteProducts || [];
    const { getFavoriteProducts, addProductKey, removeProductKey } = useFavoriteProductsState(initialState);

    const addToFavorite = async (idProduct, idProductAttribute, refreshList = 0) => {
        return new Promise((resolve, reject) => {
            $.ajax({
                url: window.addToFavoriteAction,
                data: {
                    id_product: idProduct,
                    id_product_attribute: idProductAttribute,
                    refresh_list: refreshList,
                },
                type : 'POST',
                success: (data) => {
                    if (data.success) {
                        addProductKey(`${idProduct}_${idProductAttribute}`);
                    }

                    resolve(data);
                },
                error: (error) => {
                    reject(error);
                },
            });
        });
    }

    const removeFromFavorite = async (idProduct, idProductAttribute, refreshList = 0) => {
        return new Promise((resolve, reject) => {
            $.ajax({
                url: window.removeFromFavoriteAction,
                data: {
                    id_product: idProduct,
                    id_product_attribute: idProductAttribute,
                    refresh_list: refreshList,
                },
                type : 'POST',
                success: (data) => {
                    if (data.success) {
                        removeProductKey(`${idProduct}_${idProductAttribute}`);
                    }

                    resolve(data);
                },
                error: (error) => {
                    reject(error);
                },
            });
        })
    }

    return {
        getFavoriteProducts,
        addToFavorite,
        removeFromFavorite,
    }
}
