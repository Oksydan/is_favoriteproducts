<?php

declare(strict_types=1);

namespace Oksydan\IsFavoriteProducts\Services;

use Context;
use Oksydan\IsFavoriteProducts\DTO\FavoriteProduct as FavoriteProductDTO;
use Oksydan\IsFavoriteProducts\Entity\FavoriteProduct;
use Oksydan\IsFavoriteProducts\Mapper\FavoriteProductMapper;
use Oksydan\IsFavoriteProducts\Repository\FavoriteProductCookieRepository;
use Oksydan\IsFavoriteProducts\Repository\FavoriteProductLegacyRepository;
use Oksydan\IsFavoriteProducts\Repository\FavoriteProductRepository;
use Oksydan\IsFavoriteProducts\Repository\ProductLegacyRepository;

class FavoriteProductService
{
    /*
     * Context
     */
    private \Context $context;

    /*
     * @var FavoriteProductRepository
     */
    private FavoriteProductRepository $favoriteProductsRepository;

    /*
     * @var FavoriteProductLegacyRepository
     */
    private FavoriteProductLegacyRepository $favoriteProductsRepositoryLegacy;

    /*
     * @var FavoriteProductsCookieRepository
     */
    private FavoriteProductCookieRepository $favoriteProductsCookieRepository;

    /*
     * @var ProductRepository
     */
    private ProductLegacyRepository $productRepository;

    private FavoriteProductMapper $favoriteProductMapper;

    protected $cachedFavoriteProducts = null;

    const FAVORITE_LIMIT_FOR_GUEST = 20;

    public function __construct(
        \Context $context,
        FavoriteProductRepository $favoriteProductsRepository,
        FavoriteProductCookieRepository $favoriteProductsCookieRepository,
        ProductLegacyRepository $productRepository,
        FavoriteProductLegacyRepository $favoriteProductsRepositoryLegacy,
        FavoriteProductMapper $favoriteProductMapper
    ) {
        $this->context = $context;
        $this->favoriteProductsRepository = $favoriteProductsRepository;
        $this->favoriteProductsCookieRepository = $favoriteProductsCookieRepository;
        $this->productRepository = $productRepository;
        $this->favoriteProductsRepositoryLegacy = $favoriteProductsRepositoryLegacy;
        $this->favoriteProductMapper = $favoriteProductMapper;
    }

    public function isCustomerLogged(): bool
    {
        return $this->context->customer->isLogged();
    }

    public function getFavoriteProducts(): array
    {
        if (!is_null($this->cachedFavoriteProducts)) {
            return $this->cachedFavoriteProducts;
        }

        if ($this->isCustomerLogged()) {
            $favoriteProducts = $this->favoriteProductsRepository->getFavoriteProductsByCustomer(
                (int) $this->context->customer->id,
                (int) $this->context->shop->id
            );

            $products = array_map(function (FavoriteProduct $favoriteProduct) {
                return $this->favoriteProductMapper->mapFavoriteProductEntityToFavoriteProductDTO($favoriteProduct);
            }, $favoriteProducts);

            $this->cachedFavoriteProducts = $products;
        } else {
            $products = $this->favoriteProductsCookieRepository->getFavoriteProducts((int) $this->context->shop->id);

            $products = array_filter($products, function ($product) {
                return $this->productRepository->checkProductActiveAndVisible(
                    $product->getIdProduct(),
                    $product->getIdProductAttribute(),
                    (int) $this->context->shop->id
                );
            });

            $this->cachedFavoriteProducts = $products;
        }

        return $this->cachedFavoriteProducts;
    }

    public function isFavoriteLimitReached(): bool
    {
        if ($this->isCustomerLogged()) {
            return false;
        } else {
            $favoriteProducts = $this->getFavoriteProducts();

            return count($favoriteProducts) >= self::FAVORITE_LIMIT_FOR_GUEST;
        }
    }

    public function getFavoriteLimit(): int
    {
        return self::FAVORITE_LIMIT_FOR_GUEST;
    }

    public function getFavoriteProductForListing(int $page = 1, int $limit = 10, string $orderBy = 'date_add', string $orderWay = 'DESC'): array
    {
        if (!$this->isCustomerLogged()) {
            $favoriteProducts = $this->getFavoriteProducts();

            $products = [];

            foreach ($favoriteProducts as $favoriteProduct) {
                $product['date_add'] = $favoriteProduct->getDateAdd();
                $product['id_product'] = $favoriteProduct->getIdProduct();
                $product['id_product_attribute'] = $favoriteProduct->getIdProductAttribute();

                $products[] = $product;
            }

            $count = count($products);

            if ($count <= ($page - 1) * $limit) {
                $page = 1 + (int) ($count / $limit);
            }

            if ($orderBy === 'date_add') {
                if (strtoupper($orderWay) === 'DESC') {
                    usort($products, function ($a, $b) {
                        return $a['date_add'] < $b['date_add'];
                    });
                } else {
                    usort($products, function ($a, $b) {
                        return $a['date_add'] > $b['date_add'];
                    });
                }
            }

            $products = array_slice($products, ($page - 1) * $limit, $limit);

            return [
                'items' => $products,
                'count' => $count,
                'page' => $page,
            ];
        } else {
            $favoriteProducts = $this->favoriteProductsRepositoryLegacy->getFavoriteProductsForListing(
                (int) $this->context->customer->id,
                (int) $this->context->shop->id,
                $page,
                $limit,
                $orderBy,
                $orderWay
            );

            $count = $this->favoriteProductsRepositoryLegacy->getCountFavoriteProductsForListing(
                (int) $this->context->customer->id,
                (int) $this->context->shop->id
            );

            return [
                'items' => $favoriteProducts,
                'count' => $count,
                'page' => $page,
            ];
        }
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

        $this->cachedFavoriteProducts = null;
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

        $this->cachedFavoriteProducts = null;
    }

    public function isProductAlreadyInFavorites(FavoriteProductDTO $favoriteProduct): bool
    {
        if ($this->isCustomerLogged()) {
            return $this->favoriteProductsRepository->isProductAlreadyInFavorites(
                $favoriteProduct->getIdProduct(),
                $favoriteProduct->getIdProductAttribute(),
                $favoriteProduct->getIdCustomer(),
                $favoriteProduct->getIdShop()
            );
        }

        return $this->favoriteProductsCookieRepository->isProductAlreadyInFavorites($favoriteProduct);
    }

    public function productExists(int $idProduct, $idProductAttribute, $idStore): bool
    {
        return $this->productRepository->isProductExistsInStore($idProduct, $idProductAttribute, $idStore);
    }

    public function mergerGuestFavoriteProductsToCustomer(int $idCustomer, int $idShop): void
    {
        $favoriteProducts = $this->favoriteProductsCookieRepository->getFavoriteProducts($idShop);

        foreach ($favoriteProducts as $favoriteProduct) {
            $favoriteProduct->setIdCustomer($idCustomer);

            if ($this->isProductAlreadyInFavorites($favoriteProduct)) {
                continue;
            }

            $this->addFavoriteProduct($favoriteProduct);
        }

        $this->favoriteProductsCookieRepository->clearFavoriteProducts();
    }
}
