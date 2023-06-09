<?php

namespace Oksydan\IsFavoriteProducts\ProductSearchProvider;

use Oksydan\IsFavoriteProducts\Services\FavoriteProductService;
use PrestaShop\PrestaShop\Core\Product\Search\ProductSearchContext;
use PrestaShop\PrestaShop\Core\Product\Search\ProductSearchProviderInterface;
use PrestaShop\PrestaShop\Core\Product\Search\ProductSearchQuery;
use PrestaShop\PrestaShop\Core\Product\Search\ProductSearchResult;
use PrestaShop\PrestaShop\Core\Product\Search\SortOrder;
use Symfony\Contracts\Translation\LocaleAwareInterface;

class FavoriteProductsSearchProvider implements ProductSearchProviderInterface
{
    protected FavoriteProductService $favoriteService;

    private LocaleAwareInterface $translator;

    public function __construct(
        FavoriteProductService $favoriteService,
        LocaleAwareInterface $translator
    ) {
        $this->translator = $translator;
        $this->favoriteService = $favoriteService;
    }

    public function runQuery(ProductSearchContext $context, ProductSearchQuery $query): ProductSearchResult
    {
        $products = $this->favoriteService->getFavoriteProductForListing(
            $query->getPage(),
            $query->getResultsPerPage(),
            $query->getSortOrder()->toLegacyOrderBy(),
            $query->getSortOrder()->toLegacyOrderWay()
        );

        $result = new ProductSearchResult();

        if ($products['page'] != $query->getPage()) {
            $query->setPage($products['page']);
        }

        $result->setProducts($products['items'])
            ->setTotalProductsCount($products['count']);

        $sortingOptions = [
            (new SortOrder('product', 'date_add', 'desc'))->setLabel(
                $this->translator->trans('Date added, newest to oldest', [], 'Shop.Theme.Catalog')
            ),
            (new SortOrder('product', 'date_add', 'asc'))->setLabel(
                $this->translator->trans('Date added, oldest to newest', [], 'Shop.Theme.Catalog')
            ),
        ];

        $result->setAvailableSortOrders($sortingOptions);

        return $result;
    }
}
