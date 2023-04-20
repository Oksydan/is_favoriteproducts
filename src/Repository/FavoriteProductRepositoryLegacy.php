<?php

declare(strict_types=1);

namespace Oksydan\IsFavoriteProducts\Repository;

use Doctrine\DBAL\Connection;

class FavoriteProductRepositoryLegacy
{
    /**
     * @var Connection
     */
    private Connection $connection;

    /**
     * @var string
     */
    private string $databasePrefix;

    /**
     * @var string
     */
    private string $table;

    /**
     * @param Connection $connection
     * @param string $databasePrefix
     */
    public function __construct(Connection $connection, string $databasePrefix)
    {
        $this->connection = $connection;
        $this->databasePrefix = $databasePrefix;
        $this->table = $this->databasePrefix . 'favorite_product';
    }

    public function getFavoriteProductsForListing(
        int $id_customer,
        int $id_shop,
        int $page,
        int $limit,
        string $orderBy,
        string $orderWay
    ): array {
        $qb = $this->connection->createQueryBuilder();

        $qb
            ->select('fp.id_product, fp.id_product_attribute')
            ->from($this->table, 'fp')
            ->where('fp.id_customer = :id_customer')
            ->andWhere('fp.id_shop = :id_shop')
            ->orderBy('fp.' . $orderBy, $orderWay)
            ->join('fp', $this->databasePrefix . 'product_shop', 'ps', 'ps.id_product = fp.id_product AND ps.id_shop = fp.id_shop')
            ->andWhere('ps.active = 1')
            ->andWhere('ps.visibility != \'none\'')
            ->setFirstResult(($page - 1) * $limit)
            ->setMaxResults($limit)
            ->setParameter('id_customer', $id_customer)
            ->setParameter('id_shop', $id_shop);

        $results = $qb->execute()->fetchAllAssociative();

        return !empty($results) ? $results : [];
    }

    public function getCountFavoriteProductsForListing(
        int $id_customer,
        int $id_shop
    ): int {
        $qb = $this->connection->createQueryBuilder();

        $qb
            ->select('COUNT(fp.id_product)')
            ->from($this->table, 'fp')
            ->where('fp.id_customer = :id_customer')
            ->andWhere('fp.id_shop = :id_shop')
            ->setParameter('id_customer', $id_customer)
            ->setParameter('id_shop', $id_shop)
            ->join('fp', $this->databasePrefix . 'product_shop', 'ps', 'ps.id_product = fp.id_product AND ps.id_shop = fp.id_shop')
            ->andWhere('ps.active = 1')
            ->andWhere('ps.visibility != \'none\'');

        return (int) $qb->execute()->fetchOne();
    }
}
