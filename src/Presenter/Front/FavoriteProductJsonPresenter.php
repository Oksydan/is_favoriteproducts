<?php

declare(strict_types=1);

namespace Oksydan\IsFavoriteProducts\Presenter\Front;

use Oksydan\IsFavoriteProducts\DTO\FavoriteProduct;

class FavoriteProductJsonPresenter implements JsonPresenterInterface
{
    public function present(FavoriteProduct $favoriteProduct): string
    {
        return $favoriteProduct->getIdProduct() . '_' . $favoriteProduct->getIdProductAttribute();
    }
}