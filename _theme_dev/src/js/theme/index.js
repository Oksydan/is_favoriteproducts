import { useFavoriteProducts } from "./components/useFavoriteProducts";
import { useFavoriteDOMHandler } from "./components/useFavoriteDOMHandler";

document.addEventListener('DOMContentLoaded', () => {
    const {
        getFavoriteProducts,
        addToFavorite,
        removeFromFavorite,
    } = useFavoriteProducts();
    const {
        getProductIdsFromKey,
        refreshButtons,
        setBtnActive,
        setBtnInactive,
    } = useFavoriteDOMHandler();

    const handleMessage = (messages, type = 'success') => {
        console.log({
            messages, type
        });
    }

    const updateTopContent = (topContent) => {
        const topContentContainer = document.querySelector('.js-favorite-top-content');

        if (topContentContainer) {
            const node = document.createElement('div');
            node.innerHTML = topContent;

            topContentContainer.replaceWith(...node.children);
        }
    }

    document.addEventListener('click', async (event) => {
        const btn = event.target.matches('[data-action="toggleFavorite"]') ? event.target : event.target.closest('[data-action="toggleFavorite"]');

        if (btn) {
            event.preventDefault();
            const { idProduct, idProductAttribute } = getProductIdsFromKey(btn.dataset.key);
            const isAdded = btn.dataset.active === 'true';

            if (isAdded) {
                const { success, messages, topContent } = await removeFromFavorite(idProduct, idProductAttribute);

                handleMessage(messages, success ? 'success' : 'error');

                if (success) {
                    setBtnInactive(btn);
                    updateTopContent(topContent);
                }
            } else {
                const { success, messages, topContent } = await addToFavorite(idProduct, idProductAttribute);

                handleMessage(messages, success ? 'success' : 'error');

                if (success) {
                    setBtnActive(btn);
                    updateTopContent(topContent);
                }
            }
        }
    }, false);

    refreshButtons();

    prestashop.on('updatedProduct', () => {
        setTimeout(refreshButtons, 1);
    });
    prestashop.on('updatedProductList', () => {
        setTimeout(refreshButtons, 1);
    });
})
