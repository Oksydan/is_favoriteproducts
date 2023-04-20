<?php

declare(strict_types=1);

namespace Oksydan\IsFavoriteProducts\Hook;

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
