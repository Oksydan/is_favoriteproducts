<?php

declare(strict_types=1);

namespace Oksydan\IsFavoriteProducts\Services;

use Oksydan\IsFavoriteProducts\Repository\FavoriteProductRepository;
use Oksydan\IsFavoriteProducts\Repository\FavoriteProductCookieRepository;
use Oksydan\IsFavortieProducts\Repository\ProductRepository;
use Oksydan\IsFavoriteProducts\DTO\FavoriteProduct as FavoriteProductDTO;
use Oksydan\IsFavoriteProducts\Entity\FavoriteProduct;
use Context;

class FavoriteProductService
{
    /*
     * Context
     */
    private Context $context;

    /*
     * @var FavoriteProductRepository
     */
    private FavoriteProductRepository $favoriteProductsRepository;

    /*
     * @var FavoriteProductsCookieRepository
     */
    private FavoriteProductCookieRepository $favoriteProductsCookieRepository;

    /*
     * @var ProductRepository
     */
    private ProductRepository $productRepository;

    public function __construct(
        Context $context,
        FavoriteProductRepository $favoriteProductsRepository,
        FavoriteProductCookieRepository $favoriteProductsCookieRepository,
        ProductRepository $productRepository
    ) {
        $this->context = $context;
        $this->favoriteProductsRepository = $favoriteProductsRepository;
        $this->favoriteProductsCookieRepository = $favoriteProductsCookieRepository;
        $this->productRepository = $productRepository;
    }

    public function isCustomerLogged(): bool
    {
        return $this->context->customer->isLogged();
    }

    public function getFavoriteProducts(): array
    {
        if ($this->isCustomerLogged()) {
            return $this->favoriteProductsRepository->getFavoriteProductsByCustomer(
                (int) $this->context->customer->id,
                (int) $this->context->shop->id
            );
        }

        return $this->favoriteProductsCookieRepository->getFavoriteProducts((int) $this->context->shop->id);
    }

    public function addFavoriteProduct(FavoriteProductDTO $favoriteProduct): void
    {
        if ($this->isCustomerLogged()) {
            $favoriteProductEntity = new FavoriteProduct();
            $favoriteProductEntity->setIdProduct($favoriteProduct->getIdProduct());
            $favoriteProductEntity->setIdProductAttribute($favoriteProduct->getIdProductAttribute());
            $favoriteProductEntity->setIdCustomer($favoriteProduct->getIdCustomer());
            $favoriteProductEntity->setIdShop($favoriteProduct->getIdShop());

            $this->favoriteProductsRepository->addFavoriteProduct($favoriteProductEntity);
        } else {
            $this->favoriteProductsCookieRepository->addFavoriteProduct($favoriteProduct);
        }
    }

    public function removeFavoriteProduct(FavoriteProductDTO $favoriteProduct): void
    {
        if ($this->isCustomerLogged()) {
            $favoriteProductEntity = $this->favoriteProductsRepository->getFavoriteProductByIds(
                $favoriteProduct->getIdProduct(),
                $favoriteProduct->getIdProductAttribute(),
                $favoriteProduct->getIdCustomer(),
                $favoriteProduct->getIdShop()
            );

            if (!$favoriteProductEntity) {
                return;
            }

            $this->favoriteProductsRepository->removeFavoriteProduct($favoriteProductEntity);
        } else {
            $this->favoriteProductsCookieRepository->removeFavoriteProduct($favoriteProduct);
        }
    }

    public function isProductExistsInStore(int $idProduct, $idProductAttribute, $idStore): bool
    {
        return $this->productRepository->isProductExistsInStore($idProduct, $idProductAttribute, $idStore);
    }
}
