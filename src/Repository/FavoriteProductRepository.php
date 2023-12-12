<?php

declare(strict_types=1);

namespace Oksydan\IsFavoriteProducts\Repository;

use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Oksydan\IsFavoriteProducts\Entity\FavoriteProduct;

class FavoriteProductRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, FavoriteProduct::class);
    }

    public function getFavoriteProductsByCustomer(int $id_customer, int $id_shop): array
    {
        return $this->findBy(['id_customer' => $id_customer, 'id_shop' => $id_shop]);
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

    public function isProductAlreadyInFavorites(int $id_product, int $id_product_attribute, int $id_customer, int $id_shop): bool
    {
        $qb = $this->createQueryBuilder('fp');

        $qb
            ->select('COUNT(fp.id_product)')
            ->where('fp.id_product = :id_product')
            ->setParameter('id_product', $id_product)
            ->andWhere('fp.id_product_attribute = :id_product_attribute')
            ->setParameter('id_product_attribute', $id_product_attribute)
            ->andWhere('fp.id_customer = :id_customer')
            ->setParameter('id_customer', $id_customer)
            ->andWhere('fp.id_shop = :id_shop')
            ->setParameter('id_shop', $id_shop)
        ;

        return (bool) $qb->getQuery()->getSingleScalarResult();
    }
}
