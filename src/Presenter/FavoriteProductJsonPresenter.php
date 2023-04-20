<?php

declare(strict_types=1);

namespace Oksydan\IsFavoriteProducts\Presenter;

use Oksydan\IsFavoriteProducts\DTO\FavoriteProduct;

class FavoriteProductJsonPresenter implements PresenterInterface
{
    public function present(FavoriteProduct $favoriteProduct): string
    {
        return $favoriteProduct->getIdProduct() . '_' . $favoriteProduct->getIdProductAttribute();
    }
}
