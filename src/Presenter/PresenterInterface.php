<?php

declare(strict_types=1);

namespace Oksydan\IsFavoriteProducts\Presenter;

use Oksydan\IsFavoriteProducts\DTO\FavoriteProduct;

interface PresenterInterface
{
    public function present(FavoriteProduct $favoriteProduct);
}
