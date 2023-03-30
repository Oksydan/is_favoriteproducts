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

    document.addEventListener('click', async (event) => {
        if (event.target.matches('[data-action="toggleFavorite"]')) {
            const { idProduct, idProductAttribute } = getProductIdsFromKey(event.target.dataset.key);
            const { success, message } = await addToFavorite(idProduct, idProductAttribute);
        }
    }, false);
})
