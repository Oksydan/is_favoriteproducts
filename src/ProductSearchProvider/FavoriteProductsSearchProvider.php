<?php

namespace Oksydan\IsFavoriteProducts\ProductSearchProvider;

use Oksydan\IsFavoriteProducts\Services\FavoriteProductService;
use PrestaShop\PrestaShop\Core\Product\Search\ProductSearchContext;
use PrestaShop\PrestaShop\Core\Product\Search\ProductSearchProviderInterface;
use PrestaShop\PrestaShop\Core\Product\Search\ProductSearchQuery;
use PrestaShop\PrestaShop\Core\Product\Search\ProductSearchResult;
use PrestaShop\PrestaShop\Core\Product\Search\SortOrder;
use PrestaShopBundle\Translation\TranslatorInterface;

class FavoriteProductsSearchProvider implements ProductSearchProviderInterface
{
    protected FavoriteProductService $favoriteService;

    private TranslatorInterface $translator;

    public function __construct(
        FavoriteProductService $favoriteService,
        \Context $context
    ) {
        $this->favoriteService = $favoriteService;
        // WE HAVE TO DO IT THIS WAY BECAUSE `TranslatorInterface` IS NOT AVAILABLE AS A SERVICE IN FRONT CONTAINER
        $this->translator = $context->getTranslator();
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
