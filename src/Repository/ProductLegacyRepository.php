<?php

declare(strict_types=1);

namespace Oksydan\IsFavoriteProducts\Repository;

use Doctrine\DBAL\Connection;

class ProductLegacyRepository
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
        $this->table = $this->dbPrefix . 'product_shop';
    }

    public function isProductExistsInStore(int $productId, int $productIdAttribute, int $storeId): bool
    {
        $qb = $this->connection->createQueryBuilder()
            ->select('count(p.id_product)')
            ->from($this->table, 'p')
            ->where('p.id_product = :id_product')
            ->andWhere('p.id_shop = :id_shop')
            ->setParameter('id_product', $productId)
            ->setParameter('id_product_attribute', $productIdAttribute)
            ->setParameter('id_shop', $storeId);

        if ($productIdAttribute > 0) {
            $qb->join(
                'p',
                $this->dbPrefix . 'product_attribute_shop', 'pa',
                'pa.id_product = p.id_product AND pa.id_shop = p.id_shop AND pa.id_product_attribute = :id_product_attribute');
        }

        return (bool) $qb->execute()->fetchOne();
    }

    public function checkProductActiveAndVisible(
        int $productId,
        int $productIdAttribute,
        int $storeId
    ): bool {
        $qb = $this->connection->createQueryBuilder();

        $qb
            ->select('count(p.id_product)')
            ->from($this->table, 'p')
            ->where('p.id_product = :id_product')
            ->andWhere('p.id_shop = :id_shop')
            ->andWhere('p.active = 1')
            ->andWhere('p.visibility != \'none\'')
            ->setParameter('id_product', $productId)
            ->setParameter('id_product_attribute', $productIdAttribute)
            ->setParameter('id_shop', $storeId);

        if ($productIdAttribute > 0) {
            $qb->join(
                'p',
                $this->dbPrefix . 'product_attribute_shop', 'pa',
                'pa.id_product = p.id_product AND pa.id_shop = p.id_shop AND pa.id_product_attribute = :id_product_attribute');
        }

        return (bool) $qb->execute()->fetchOne();
    }
}
