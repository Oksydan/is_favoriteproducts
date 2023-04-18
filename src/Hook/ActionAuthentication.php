<?php

declare(strict_types=1);

namespace Oksydan\IsFavoriteProducts\Hook;

use Oksydan\IsFavoriteProducts\Presenter\FavoriteProductJsonPresenter;
use Oksydan\IsFavoriteProducts\Services\FavoriteProductService;
use Oksydan\IsFavoriteProducts\DTO\FavoriteProduct;
use Context;
use Is_favoriteproducts;

class ActionAuthentication extends AbstractHook
{
    public function execute(array $params): void
    {
        if (empty($params['customer']) || !($params['customer'] instanceof \Customer)) {
            return;
        }

        $this->favoriteProductService->mergerGuestFavoriteProductsToCustomer((int) $params['customer']->id, (int) $this->context->shop->id);
    }
}
