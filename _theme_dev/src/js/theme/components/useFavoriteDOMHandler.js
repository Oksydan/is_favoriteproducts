import { useFavoriteProductsState } from './useFavoriteProductsState';

export const useFavoriteDOMHandler = (buttonsSelector = '[data-action="toggleFavorite"]') => {
    const { getFavoriteProducts } = useFavoriteProductsState();
    const getButtons = () => document.querySelectorAll(buttonsSelector);
    const activeDataAttribute = 'active';

    const setBtnActive = (btn) => {
        const key = btn.dataset.key;
        const allButtons = getAllButtonsByProductKey(key);

        allButtons.forEach((btn) => {
            btn.dataset[activeDataAttribute] = true;
        });
    }

    const setBtnInactive = (btn) => {
        const key = btn.dataset.key;
        const allButtons = getAllButtonsByProductKey(key);

        allButtons.forEach((btn) => {
            btn.dataset[activeDataAttribute] = false;
        });
    }

    const refreshButtons = () => {
        getButtons().forEach((btn) => {
            btn.dataset[activeDataAttribute] = false;
        });

        getFavoriteProducts().forEach((product_key) => {
            const allButtons = getAllButtonsByProductKey(product_key);

            allButtons.forEach((btn) => {
                btn.dataset[activeDataAttribute] = true;
            });
        })
    }

    const getAllButtonsByProductKey = (key) => {
        return document.querySelectorAll(`${buttonsSelector}[data-key="${key}"]`);
    }

    const getProductIdsFromKey = (key) => {
        const [idProduct, idProductAttribute] = key.split('_');
        return {
            idProduct: parseInt(idProduct, 10),
            idProductAttribute: parseInt(idProductAttribute, 10),
        }
    }

    return {
        getProductIdsFromKey,
        refreshButtons,
        setBtnActive,
        setBtnInactive,
    }
}
