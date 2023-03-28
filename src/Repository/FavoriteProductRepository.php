<?php

declare(strict_types=1);

namespace Oksydan\IsFavoriteProducts\Repository;

use Doctrine\ORM\EntityRepository;
use Oksydan\IsFavoriteProducts\Entity\FavoriteProduct;

class FavoriteProductRepository extends EntityRepository
{

    public function getFavoriteProductsByCustomer(int $id_customer, int $id_shop): array
    {
        $qb = $this->createQueryBuilder('fp');

        $qb
            ->select('fp.id_product', 'fp.id_product_attribute')
            ->where('fp.id_customer = :id_customer')
            ->andWhere('fp.id_shop = :id_shop')
            ->setParameter('id_customer', $id_customer)
            ->setParameter('id_shop', $id_shop)
        ;

        return $qb->getQuery()->getArrayResult();
    }

    public function getLatestAddedProductForShop(\DateTimeImmutable $date_add, int $id_shop): array
    {
        $qb = $this->createQueryBuilder('fp');

        $qb
            ->select('fp.id_product', 'fp.id_product_attribute')
            ->where('fp.date_add > :date_add')
            ->setParameter('date_add', $date_add)
            ->andWhere('fp.id_shop = :id_shop')
            ->setParameter('id_shop', $id_shop)
        ;

        return $qb->getQuery()->getArrayResult();
    }

    public function addFavoriteProduct(FavoriteProduct $favoriteProduct): void
    {
        $this->getEntityManager()->persist($favoriteProduct);
        $this->getEntityManager()->flush();
    }

    public function getFavoriteProductByIds(int $id_product, int $id_product_attribute, int $id_customer, int $id_shop): ?FavoriteProduct
    {
        $qb = $this->createQueryBuilder('fp');

        $qb
            ->where('fp.id_product = :id_product')
            ->setParameter('id_product', $id_product)
            ->andWhere('fp.id_product_attribute = :id_product_attribute')
            ->setParameter('id_product_attribute', $id_product_attribute)
            ->andWhere('fp.id_customer = :id_customer')
            ->setParameter('id_customer', $id_customer)
            ->andWhere('fp.id_shop = :id_shop')
            ->setParameter('id_shop', $id_shop)
        ;

        return $qb->getQuery()->getOneOrNullResult();
    }

    public function removeFavoriteProduct(FavoriteProduct $favoriteProduct): void
    {
        $this->getEntityManager()->remove($favoriteProduct);
        $this->getEntityManager()->flush();
    }
}
