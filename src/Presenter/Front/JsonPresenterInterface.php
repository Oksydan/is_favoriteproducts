<?php

declare(strict_types=1);

namespace Oksydan\IsFavoriteProducts\Presenter\Front;

use Oksydan\IsFavoriteProducts\DTO\FavoriteProduct;

interface JsonPresenterInterface
{
    public function present(FavoriteProduct $favoriteProduct): string;
}
