<?php

declare(strict_types=1);

namespace Oksydan\IsFavoriteProducts\Provider;

interface DateFiltersProviderInterface
{
    /**
     * @return array
     */
    public function getDateFilters(): array;
}
