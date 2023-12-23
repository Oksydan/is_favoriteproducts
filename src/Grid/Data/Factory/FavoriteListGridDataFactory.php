<?php

declare(strict_types=1);

namespace Oksydan\IsFavoriteProducts\Grid\Data\Factory;

use PrestaShop\PrestaShop\Core\Grid\Data\Factory\GridDataFactoryInterface;
use PrestaShop\PrestaShop\Core\Grid\Data\GridData;
use PrestaShop\PrestaShop\Core\Grid\Record\RecordCollection;
use PrestaShop\PrestaShop\Core\Grid\Search\SearchCriteriaInterface;

final class FavoriteListGridDataFactory implements GridDataFactoryInterface
{
    /**
     * @var GridDataFactoryInterface
     */
    private GridDataFactoryInterface $favoriteListDataFactory;

    public function __construct(
        GridDataFactoryInterface $favoriteListDataFactory
    ) {
        $this->favoriteListDataFactory = $favoriteListDataFactory;
    }

    /**
     * {@inheritdoc}
     */
    public function getData(SearchCriteriaInterface $searchCriteria)
    {
        $favoriteListData = $this->favoriteListDataFactory->getData($searchCriteria);

        return new GridData(
            new RecordCollection($favoriteListData->getRecords()->all()),
            $favoriteListData->getRecordsTotal(),
            $favoriteListData->getQuery()
        );
    }
}
