<?php

declare(strict_types=1);

namespace Oksydan\IsFavoriteProducts\Presenter\Admin;

use Oksydan\IsFavoriteProducts\DTO\StatFavoriteProduct;

interface AdminStatsFavoriteProductPresenterInterface
{
    public function present(StatFavoriteProduct $favoriteProduct, \Language $language): array;
}
