<?php

declare(strict_types=1);

namespace Oksydan\IsFavoriteProducts\Controller;

use Oksydan\IsFavoriteProducts\Grid\Filter\FavoriteListFilters;
use Oksydan\IsFavoriteProducts\Provider\DateFiltersProvider;
use PrestaShopBundle\Controller\Admin\FrameworkBundleAdminController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class AdminFavoriteController extends FrameworkBundleAdminController
{
    public function indexAction(Request $request, FavoriteListFilters $favoriteListFilters): Response
    {
        $favoriteGridFactory = $this->get('oksydan.is_favoriteproducts.grid.favorite_list_grid_factory');

        $favoriteListFilters = $this->getFiltersByDateRange($request, $favoriteListFilters);

        $favoriteGrid = $favoriteGridFactory->getGrid($favoriteListFilters);

        return $this->render('@Modules/is_favoriteproducts/views/templates/admin/grid/index.html.twig', [
            'grid' => $this->presentGrid($favoriteGrid),
            'tabs' => $this->buildDateTabs($request),
            'title' => $this->trans('Favorite products analytics', 'Modules.Isfavoriteproducts.Admin'),
        ]);
    }

    private function buildDateTabs(Request $request): array
    {
        $tabs = [];
        $dateFiltersProvider = $this->get(DateFiltersProvider::class);
        $activeTab = $request->query->get('date_range') ?? DateFiltersProvider::ALL_TIME;

        foreach ($dateFiltersProvider->getDateFilters() as $filter) {
            $tabs[] = [
                'name' => $filter,
                'title' => $this->getTitleByDateRange($filter),
                'url' => $this->generateUrl('is_favoriteproducts_controller_index', [
                    'date_range' => $filter,
                ]),
                'active' => $filter === $activeTab,
            ];
        }

        return $tabs;
    }

    private function getFiltersByDateRange(Request $request, FavoriteListFilters $favoriteListFilters): FavoriteListFilters
    {
        $dateRange = $request->query->get('date_range');
        $dateFiltersProvider = $this->get(DateFiltersProvider::class);
        $dateTime = new \DateTime();

        if (
            null === $dateRange ||
            DateFiltersProvider::ALL_TIME === $dateRange ||
            !in_array($dateRange, $dateFiltersProvider->getDateFilters())
        ) {
            return $favoriteListFilters;
        }

        switch ($dateRange) {
            case DateFiltersProvider::LAST_24_HOURS:
                $favoriteListFilters->addFilter([
                    'data_add' => $dateTime->modify('-1 day'),
                ]);
                break;
            case DateFiltersProvider::LAST_3_DAYS:
                $favoriteListFilters->addFilter([
                    'date_add' => $dateTime->modify('-3 days'),
                ]);
                break;
            case DateFiltersProvider::LAST_7_DAYS:
                $favoriteListFilters->addFilter([
                    'date_add' => $dateTime->modify('-7 days'),
                ]);
                break;
            case DateFiltersProvider::LAST_30_DAYS:
                $favoriteListFilters->addFilter([
                    'date_add' => $dateTime->modify('-30 days'),
                ]);
                break;
            case DateFiltersProvider::LAST_90_DAYS:
                $favoriteListFilters->addFilter([
                    'date_add' => $dateTime->modify('-90 days'),
                ]);
                break;
        }

        $favoriteListFilters->addFilter([
            'date_add' => $dateTime,
        ]);

        return $favoriteListFilters;
    }

    private function getTitleByDateRange(string $filterName): string
    {
        $title = '';

        switch ($filterName) {
            case DateFiltersProvider::ALL_TIME:
                $title = $this->trans('All time', 'Modules.Isfavoriteproducts.Admin');
                break;
            case DateFiltersProvider::LAST_24_HOURS:
                $title = $this->trans('Last 24 hours', 'Modules.Isfavoriteproducts.Admin');
                break;
            case DateFiltersProvider::LAST_3_DAYS:
                $title = $this->trans('Last 3 days', 'Modules.Isfavoriteproducts.Admin');
                break;
            case DateFiltersProvider::LAST_7_DAYS:
                $title = $this->trans('Last 7 days', 'Modules.Isfavoriteproducts.Admin');
                break;
            case DateFiltersProvider::LAST_30_DAYS:
                $title = $this->trans('Last 30 days', 'Modules.Isfavoriteproducts.Admin');
                break;
            case DateFiltersProvider::LAST_90_DAYS:
                $title = $this->trans('Last 90 days', 'Modules.Isfavoriteproducts.Admin');
                break;
        }

        return $title;
    }
}
