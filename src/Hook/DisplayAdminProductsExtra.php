<?php

declare(strict_types=1);

namespace Oksydan\IsFavoriteProducts\Hook;

use Oksydan\IsFavoriteProducts\Services\FavoriteProductService;
use Oksydan\IsFavoriteProducts\View\Admin\RenderAdminProductFavoriteStats;

class DisplayAdminProductsExtra extends AbstractHook
{
    /**
     * @var RenderAdminProductFavoriteStats
     */
    private RenderAdminProductFavoriteStats $renderAdminProductFavoriteStats;

    /**
     * @param \Is_favoriteproducts $module
     * @param \Context $context
     * @param FavoriteProductService $favoriteProductService
     * @param RenderAdminProductFavoriteStats $renderAdminProductFavoriteStats
     */
    public function __construct(
        \Is_favoriteproducts $module,
        \Context $context,
        FavoriteProductService $favoriteProductService,
        RenderAdminProductFavoriteStats $renderAdminProductFavoriteStats
    ) {
        parent::__construct($module, $context, $favoriteProductService);
        $this->renderAdminProductFavoriteStats = $renderAdminProductFavoriteStats;
    }

    public function execute(array $params): string
    {
        $productId = (int) $params['id_product'];

        return $this->renderAdminProductFavoriteStats->render($productId);
    }
}
