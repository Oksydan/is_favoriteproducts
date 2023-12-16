<?php

declare(strict_types=1);

namespace Oksydan\IsFavoriteProducts\Provider;


interface CustomerFavoriteProductsProviderInterface
{
    public function getFavoriteProductsByCustomer(\Customer $customer, \Shop $shop): array;
}
