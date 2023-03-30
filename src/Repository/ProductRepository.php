<?php

declare(strict_types=1);

namespace Oksydan\IsFavoriteProducts\Repository;

use Doctrine\DBAL\Connection;

class ProductRepository
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
    public function __construct(Connection $connection, $databasePrefix)
    {
        $this->connection = $connection;
        $this->databasePrefix = $databasePrefix;
        $this->table = $this->databasePrefix . 'product_shop';
    }

    public function isProductExistsInStore($productId, $productIdAttribute, $storeId): bool
    {
        $qb = $this->connection->createQueryBuilder()
            ->select('p.id_product')
            ->from($this->table, 'p')
            ->where('p.id_product = :id_product')
            ->andWhere('p.id_shop = :id_shop')
            ->join(
                'p',
                $this->databasePrefix . 'product_attribute_shop', 'pa',
                'pa.id_product = p.id_product AND pa.id_shop = p.id_shop AND pa.id_product_attribute = :id_product_attribute')
            ->setParameter('id_product', $productId)
            ->setParameter('id_product_attribute', $productIdAttribute)
            ->setParameter('id_shop', $storeId);

        return (bool) $qb->execute()->columnCount();
    }
}
