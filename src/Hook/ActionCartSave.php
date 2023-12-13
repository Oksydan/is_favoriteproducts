<?php

declare(strict_types=1);

namespace Oksydan\IsFavoriteProducts\Hook;

use Oksydan\IsFavoriteProducts\Services\FavoriteProductService;

class ActionCartSave extends AbstractHook
{
    private DisplayCrossSellingShoppingCart $displayCrossSellingShoppingCart;

    public function __construct(
        \Is_favoriteproducts $module,
        \Context $context,
        FavoriteProductService $favoriteProductService,
        DisplayCrossSellingShoppingCart $displayCrossSellingShoppingCart
    ) {
        parent::__construct($module, $context, $favoriteProductService);
        $this->displayCrossSellingShoppingCart = $displayCrossSellingShoppingCart;
    }

    public function execute(array $params): void
    {
        if (empty($this->context->cart->id)) {
            return;
        }

        $this->displayCrossSellingShoppingCart->clearCache();
    }
}
