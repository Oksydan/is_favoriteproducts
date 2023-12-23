<?php

declare(strict_types=1);

namespace Oksydan\IsFavoriteProducts\Grid\Query;

use Doctrine\DBAL\Connection;
use Doctrine\DBAL\Query\QueryBuilder;
use PrestaShop\PrestaShop\Adapter\Configuration;
use PrestaShop\PrestaShop\Adapter\Shop\Context;
use PrestaShop\PrestaShop\Core\Grid\Query\AbstractDoctrineQueryBuilder;
use PrestaShop\PrestaShop\Core\Grid\Search\SearchCriteriaInterface;

final class FavoriteListQueryBuilder extends AbstractDoctrineQueryBuilder
{
    /**
     * @var Context
     */
    private Context $contextAdapter;

    /**
     * @var \Context
     */
    private \Context $context;

    /**
     * @var Configuration
     */
    private Configuration $configuration;

    /**
     * MenuListQueryBuilder constructor.
     *
     * @param Connection $connection
     * @param $dbPrefix
     * @param Context $contextAdapter
     */
    public function __construct(
        Connection $connection,
        $dbPrefix,
        Context $contextAdapter,
        \Context $context,
        Configuration $configuration
    ) {
        parent::__construct($connection, $dbPrefix);

        $this->contextAdapter = $contextAdapter;
        $this->context = $context;
        $this->configuration = $configuration;
    }

    /**
     * @param SearchCriteriaInterface $searchCriteria
     *
     * @return QueryBuilder
     */
    public function getSearchQueryBuilder(SearchCriteriaInterface $searchCriteria): QueryBuilder
    {
        $qb = $this->getBaseQuery();
        $qb->select('fp.id_product, COUNT(fp.id_product) as count, pl.name');

        $qb->orderBy(
            $searchCriteria->getOrderBy(),
            $searchCriteria->getOrderWay()
        )
        ->setFirstResult($searchCriteria->getOffset())
        ->setMaxResults($searchCriteria->getLimit());

        return $qb;
    }

    /**
     * @param SearchCriteriaInterface $searchCriteria
     *
     * @return QueryBuilder
     */
    public function getCountQueryBuilder(SearchCriteriaInterface $searchCriteria): QueryBuilder
    {
        $qb = $this->getBaseQuery();
        $qb->select('COUNT(fp.id_product)');

        return $qb;
    }

    /**
     * @return QueryBuilder
     */
    private function getBaseQuery(): QueryBuilder
    {
        $qb = $this->connection
            ->createQueryBuilder()
            ->from($this->dbPrefix . 'favorite_product', 'fp');

        if (\Shop::isFeatureActive() && !$this->contextAdapter->isAllShopContext()) {
            $qb->join('fp', $this->dbPrefix . 'product_shop', 'p', 'p.id_product = fp.id_product')
                ->join('p', $this->dbPrefix . 'product_lang', 'pl', 'pl.id_product = p.id_product')
                ->andWhere('p.id_shop = ' . $this->contextAdapter->getContextShopID())
                ->andWhere('pl.id_shop = ' . $this->contextAdapter->getContextShopID())
                ->andWhere('pl.id_lang = ' . $this->context->language->id)
                ->andWhere('p.id_product IS NOT NULL');
        } else {
            $qb->join('fp', $this->dbPrefix . 'product_shop', 'p', 'p.id_product = fp.id_product')
                ->join('p', $this->dbPrefix . 'product_lang', 'pl', 'pl.id_product = p.id_product')
                ->andWhere('p.id_shop = ' . $this->configuration->get('PS_SHOP_DEFAULT'))
                ->andWhere('pl.id_shop = ' . $this->configuration->get('PS_SHOP_DEFAULT'))
                ->andWhere('pl.id_lang = ' . $this->context->language->id)
                ->andWhere('p.id_product IS NOT NULL');
        }

        $qb->groupBy('fp.id_product');

        return $qb;
    }
}
