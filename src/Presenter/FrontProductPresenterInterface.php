<?php

declare(strict_types=1);

namespace Oksydan\IsFavoriteProducts\Presenter;

use PrestaShop\PrestaShop\Adapter\Presenter\Product\ProductLazyArray;

interface FrontProductPresenterInterface
{
    public function present(array $product): ProductLazyArray;
}
