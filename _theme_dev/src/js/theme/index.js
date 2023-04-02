import { useFavoriteProducts} from "./components/useFavoriteProducts";

document.addEventListener('DOMContentLoaded', () => {
    const {
        getFavoriteProducts,
        addToFavorite,
        removeFromFavorite,
    } = useFavoriteProducts();

    const favoriteProducts = getFavoriteProducts();
    console.log(favoriteProducts);

    const getProductIdsFromKey = (key) => {
        const [idProduct, idProductAttribute] = key.split('_');
        return {
            idProduct: parseInt(idProduct, 10),
            idProductAttribute: parseInt(idProductAttribute, 10),
        }
    }

    const handleMessage = (messages, type = 'success') => {
        console.log({
            messages, type
        });
    }

    document.addEventListener('click', async (event) => {
        const btn = event.target.matches('[data-action="toggleFavorite"]') ? event.target : event.target.closest('[data-action="toggleFavorite"]');

        if (btn) {
            event.preventDefault();
            const { idProduct, idProductAttribute } = getProductIdsFromKey(btn.dataset.key);
            const isAdded = btn.dataset.active === 'true';

            if (isAdded) {
                const { success, messages } = await removeFromFavorite(idProduct, idProductAttribute);

                handleMessage(messages, success ? 'success' : 'error');
            } else {
                const { success, messages } = await addToFavorite(idProduct, idProductAttribute);

                handleMessage(messages, success ? 'success' : 'error');
            }
        }
    }, false);
})
