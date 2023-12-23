<?php

declare(strict_types=1);

namespace Oksydan\IsFavoriteProducts\Controller;

use Oksydan\IsFavoriteProducts\Grid\Filter\FavoriteListFilters;
use PrestaShopBundle\Controller\Admin\FrameworkBundleAdminController;
use Symfony\Component\HttpFoundation\Response;

class AdminFavoriteController extends FrameworkBundleAdminController
{
    public function indexAction(FavoriteListFilters $favoriteListFilters): Response
    {
        $favoriteGridFactory = $this->get('oksydan.is_favoriteproducts.grid.favorite_list_grid_factory');

        $favoriteGrid = $favoriteGridFactory->getGrid($favoriteListFilters);

        return $this->render('@Modules/is_favoriteproducts/views/templates/admin/grid/index.html.twig', [
            'grid' => $this->presentGrid($favoriteGrid),
        ]);
    }
}
