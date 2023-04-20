import useFavoriteProductsState from './useFavoriteProductsState';

const useFavoriteDOMHandler = (buttonsSelector = '[data-action="toggleFavorite"]') => {
  const { getFavoriteProducts } = useFavoriteProductsState();
  const getButtons = () => document.querySelectorAll(buttonsSelector);
  const activeDataAttribute = 'active';

  const getAllButtonsByProductKey = (key) => document.querySelectorAll(`${buttonsSelector}[data-key="${key}"]`);

  const getProductIdsFromKey = (key) => {
    const [idProduct, idProductAttribute] = key.split('_');
    return {
      idProduct: parseInt(idProduct, 10),
      idProductAttribute: parseInt(idProductAttribute, 10),
    };
  };

  const setBtnActive = (btn) => {
    const { key } = btn.dataset;
    const allButtons = getAllButtonsByProductKey(key);

    allButtons.forEach((currentBtn) => {
      currentBtn.dataset[activeDataAttribute] = true;
    });
  };

  const setBtnInactive = (btn) => {
    const { key } = btn.dataset;
    const allButtons = getAllButtonsByProductKey(key);

    allButtons.forEach((currentBtn) => {
      currentBtn.dataset[activeDataAttribute] = false;
    });
  };

  const refreshButtons = () => {
    getButtons().forEach((btn) => {
      btn.dataset[activeDataAttribute] = false;
    });

    getFavoriteProducts().forEach((productKey) => {
      const allButtons = getAllButtonsByProductKey(productKey);

      allButtons.forEach((btn) => {
        btn.dataset[activeDataAttribute] = true;
      });
    });
  };

  return {
    getProductIdsFromKey,
    refreshButtons,
    setBtnActive,
    setBtnInactive,
  };
};

export default useFavoriteDOMHandler;
