<?php

declare(strict_types=1);

namespace Oksydan\IsFavoriteProducts\Presenter;

use Oksydan\IsFavoriteProducts\DTO\FavoriteProduct;

class FavoriteProductJsonPresenter implements PresenterInterface
{
    public function present(FavoriteProduct $favoriteProduct): array
    {
        return [
            'id_product' => $favoriteProduct->getIdProduct(),
            'id_product_attribute' => $favoriteProduct->getIdProductAttribute(),
            'product_key' => $favoriteProduct->getIdProduct() . '_' . $favoriteProduct->getIdProductAttribute(),
        ];
    }
}
