<?php

declare(strict_types=1);

namespace Oksydan\IsFavoriteProducts\Presenter\Admin;

use Oksydan\IsFavoriteProducts\DTO\FavoriteProduct;

interface AdminFavoriteProductPresenterInterface
{
    public function present(FavoriteProduct $favoriteProduct, \Language $language): array;
}
