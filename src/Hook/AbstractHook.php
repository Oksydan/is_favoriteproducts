<?php

declare(strict_types=1);

namespace Oksydan\IsFavoriteProducts\Hook;

use Context;
use Is_favoriteproducts;
use Oksydan\IsFavoriteProducts\Services\FavoriteProductService;

abstract class AbstractHook implements HookInterface
{
    /**
     * @var Is_favoriteproducts
     */
    protected Is_favoriteproducts $module;

    /**
     * @var Context
     */
    protected Context $context;

    /**
     * @var FavoriteProductService
     */
    protected FavoriteProductService $favoriteProductService;

    public function __construct(
        Is_favoriteproducts $module,
        Context $context,
        FavoriteProductService $favoriteProductService
    )
    {
        $this->module = $module;
        $this->context = $context;
        $this->favoriteProductService = $favoriteProductService;
    }
}
