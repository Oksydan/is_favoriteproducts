import wretch from 'wretch';
import QueryStringAddon from 'wretch/addons/queryString';
import useFavoriteProductsState from './useFavoriteProductsState';

const useFavoriteProducts = () => {
  // favoriteProducts is a global variable set via Media::addJsDef
  const initialState = window.favoriteProducts || [];
  const { getFavoriteProducts, addProductKey, removeProductKey } = useFavoriteProductsState(initialState);

  const getWretch = (url) => wretch(url).addon(QueryStringAddon);

  const addToFavorite = async (idProduct, idProductAttribute, refreshList = 0) => new Promise((resolve, reject) => {
    // addToFavoriteAction is a global variable set via Media::addJsDef
    getWretch(window.addToFavoriteAction)
      .query({
        id_product: idProduct,
        id_product_attribute: idProductAttribute,
        refresh_list: refreshList,
      })
      .post()
      .json((data) => {
        if (data.success) {
          addProductKey(`${idProduct}_${idProductAttribute}`);
        }

        resolve(data);
      })
      .catch(() => {
        reject(Error('Something went wrong'));
      });
  });

  const removeFromFavorite = async (idProduct, idProductAttribute, refreshList = 0) => new Promise((resolve, reject) => {
    // removeFromFavoriteAction is a global variable set via Media::addJsDef
    getWretch(window.removeFromFavoriteAction)
      .query({
        id_product: idProduct,
        id_product_attribute: idProductAttribute,
        refresh_list: refreshList,
      })
      .post()
      .json((data) => {
        if (data.success) {
          removeProductKey(`${idProduct}_${idProductAttribute}`);
        }

        resolve(data);
      })
      .catch(() => {
        reject(Error('Something went wrong'));
      });
  });

  return {
    getFavoriteProducts,
    addToFavorite,
    removeFromFavorite,
  };
};

export default useFavoriteProducts;
