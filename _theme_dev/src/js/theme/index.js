import useAlertToast from '@js/theme/components/useAlertToast';
import useFavoriteProducts from './components/useFavoriteProducts';
import useFavoriteDOMHandler from './components/useFavoriteDOMHandler';
import { parseToHtml } from '@js/utils/DOM/DOMHelpers';

DOMReady(() => {
  const {
    addToFavorite,
    removeFromFavorite,
    updateUpSellingBLock,
  } = useFavoriteProducts();
  const {
    getProductIdsFromKey,
    refreshButtons,
    setBtnActive,
    setBtnInactive,
  } = useFavoriteDOMHandler();
  const {
    success,
    danger,
  } = useAlertToast();

  const handleMessage = (messages, type = 'success') => {
    if (type === 'success') {
      success(messages);
    } else {
      danger(messages);
    }
  };

  const updateTopContent = (topContent) => {
    const topContentContainer = document.querySelector('.js-favorite-top-content');

    if (topContentContainer) {
      const node = document.createElement('div');
      node.innerHTML = topContent;

      topContentContainer.replaceWith(...node.children);
    }
  };

  eventHandlerOn(document, 'click', '[data-action="toggleFavorite"]', async (event) => {
    const btn = event.delegateTarget;

    if (btn) {
      event.preventDefault();
      const { idProduct, idProductAttribute } = getProductIdsFromKey(btn.dataset.key);
      const isAdded = btn.dataset.active === 'true';

      if (isAdded) {
        try {
          const { success: requestSuccess, messages, topContent } = await removeFromFavorite(idProduct, idProductAttribute);

          handleMessage(messages, requestSuccess ? 'success' : 'error');

          if (requestSuccess) {
            setBtnInactive(btn);
            updateTopContent(topContent);
          }
        } catch (error) {
          handleMessage([error.message], 'error');
        }
      } else {
        try {
          const { success: requestSuccess, messages, topContent } = await addToFavorite(idProduct, idProductAttribute);

          handleMessage(messages, requestSuccess ? 'success' : 'error');

          if (requestSuccess) {
            setBtnActive(btn);
            updateTopContent(topContent);
          }
        } catch (error) {
          handleMessage([error.message], 'error');
        }
      }

      if (window.isFavoriteProductsListingPage) {
        prestashop.emit('updateFacets', window.location.href);
      }
    }
  });

  refreshButtons();

  prestashop.on('updatedProduct', () => {
    setTimeout(refreshButtons, 1);
  });
  prestashop.on('updatedProductList', () => {
    setTimeout(refreshButtons, 1);
  });
  prestashop.on('updatedCart', async () => {
    const upSellingBlock = document.querySelector('.js-favorite-up-selling-block');

    if (!upSellingBlock) {
      return;
    }

    try {
      const { content } = await updateUpSellingBLock();

      if (content) {
        upSellingBlock.replaceWith(parseToHtml(content));
        prestashop.emit('updatedFavoriteUpSellingBlock', content);
      }
    } catch (error) {
    }
  });
  prestashop.on('updatedFavoriteUpSellingBlock', () => {
    setTimeout(refreshButtons, 1);
    prestashop.pageSlider.refresh();
  });
});
