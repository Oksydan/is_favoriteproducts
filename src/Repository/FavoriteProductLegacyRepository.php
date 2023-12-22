<?php

declare(strict_types=1);

namespace Oksydan\IsFavoriteProducts\Repository;

use Doctrine\DBAL\Connection;
use Oksydan\IsFavoriteProducts\DTO\FavoriteProduct as FavoriteProductDTO;

class FavoriteProductLegacyRepository
{
    /**
     * @var Connection
     */
    private Connection $connection;

    /**
     * @var string
     */
    private string $dbPrefix;

    /**
     * @var string
     */
    private string $table;

    /**
     * @param Connection $connection
     * @param string $dbPrefix
     */
    public function __construct(Connection $connection, string $dbPrefix)
    {
        $this->connection = $connection;
        $this->dbPrefix = $dbPrefix;
        $this->table = $this->dbPrefix . 'favorite_product';
    }

    /**
     * @param int $id_customer
     * @param int $id_shop
     * @param int $page
     * @param int $limit
     * @param string $orderBy
     * @param string $orderWay
     * @param FavoriteProductDTO[] $excludeProducts
     *
     * @return array
     *
     * @throws \Doctrine\DBAL\Driver\Exception
     * @throws \Doctrine\DBAL\Exception
     */
    public function getFavoriteProductsForListing(
        int $id_customer,
        int $id_shop,
        int $page,
        int $limit,
        string $orderBy,
        string $orderWay,
        array $excludeProducts = []
    ): array {
        $qb = $this->connection->createQueryBuilder();

        $qb
            ->select('fp.id_product, fp.id_product_attribute')
            ->from($this->table, 'fp')
            ->where('fp.id_customer = :id_customer')
            ->andWhere('fp.id_shop = :id_shop')
            ->orderBy('fp.' . $orderBy, $orderWay)
            ->join('fp', $this->dbPrefix . 'product_shop', 'ps', 'ps.id_product = fp.id_product AND ps.id_shop = fp.id_shop')
            ->andWhere('ps.active = 1')
            ->andWhere('ps.visibility != \'none\'')
            ->setFirstResult(($page - 1) * $limit)
            ->setMaxResults($limit)
            ->setParameter('id_customer', $id_customer)
            ->setParameter('id_shop', $id_shop);

        if (!empty($excludeProducts)) {
            $productKeys = [];
            foreach ($excludeProducts as $excludeProduct) {
                $key = $excludeProduct->getIdProduct() . '_' . $excludeProduct->getIdProductAttribute();

                if (!in_array($key, $productKeys)) {
                    $productKeys[] = $key;
                }
            }

            $qb
                ->andWhere('CONCAT(fp.id_product, \'_\', fp.id_product_attribute) NOT IN (:product_keys)')
                ->setParameter('product_keys', $productKeys, Connection::PARAM_STR_ARRAY);
        }

        $results = $qb->execute()->fetchAllAssociative();

        return !empty($results) ? $results : [];
    }

    /**
     * @param int $id_customer
     * @param int $id_shop
     * @param FavoriteProductDTO[] $excludeProducts
     *
     * @return int
     *
     * @throws \Doctrine\DBAL\Driver\Exception
     * @throws \Doctrine\DBAL\Exception
     */
    public function getCountFavoriteProductsForListing(
        int $id_customer,
        int $id_shop,
        array $excludeProducts = []
    ): int {
        $qb = $this->connection->createQueryBuilder();

        $qb
            ->select('COUNT(fp.id_product)')
            ->from($this->table, 'fp')
            ->where('fp.id_customer = :id_customer')
            ->andWhere('fp.id_shop = :id_shop')
            ->setParameter('id_customer', $id_customer)
            ->setParameter('id_shop', $id_shop)
            ->join('fp', $this->dbPrefix . 'product_shop', 'ps', 'ps.id_product = fp.id_product AND ps.id_shop = fp.id_shop')
            ->andWhere('ps.active = 1')
            ->andWhere('ps.visibility != \'none\'');

        if (!empty($excludeProducts)) {
            foreach ($excludeProducts as $excludeProduct) {
                $qb
                    ->andWhere('fp.id_product != :id_product_' . $excludeProduct->getIdProduct())
                    ->andWhere('fp.id_product_attribute != :id_product_attribute_' . $excludeProduct->getIdProductAttribute())
                    ->setParameter('id_product_' . $excludeProduct->getIdProduct(), $excludeProduct->getIdProduct())
                    ->setParameter('id_product_attribute_' . $excludeProduct->getIdProductAttribute(), $excludeProduct->getIdProductAttribute());
            }
        }

        return (int) $qb->execute()->fetchOne();
    }

    public function getFavoriteStatsForProduct(int $idProduct, $idShop = null): array
    {
        $qb = $this->connection->createQueryBuilder();

        $qb
            ->select('fp.id_product, fp.id_product_attribute')
            ->from($this->table, 'fp')
            ->where('fp.id_product = :id_product')
            ->setParameter('id_product', $idProduct);

        if ($idShop) {
            $qb
                ->andWhere('fp.id_shop = :id_shop')
                ->setParameter('id_shop', $idShop);
        }

        $results = $qb->execute()->fetchAllAssociative();

        return !empty($results) ? $results : [];
    }
}
