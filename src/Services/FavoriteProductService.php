<?php

declare(strict_types=1);

namespace Oksydan\IsFavoriteProducts\Services;

use Oksydan\IsFavoriteProducts\Repository\FavoriteProductRepository;
use Oksydan\IsFavoriteProducts\Repository\FavoriteProductCookieRepository;
use Oksydan\IsFavoriteProducts\Repository\ProductRepository;
use Oksydan\IsFavoriteProducts\DTO\FavoriteProduct as FavoriteProductDTO;
use Oksydan\IsFavoriteProducts\Entity\FavoriteProduct;
use Context;
use Oksydan\IsFavoriteProducts\Mapper\FavoriteProductMapper;

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

    private FavoriteProductMapper $favoriteProductMapper;

    protected $cachedFavoriteProducts = null;

    public function __construct(
        Context $context,
        FavoriteProductRepository $favoriteProductsRepository,
        FavoriteProductCookieRepository $favoriteProductsCookieRepository,
        ProductRepository $productRepository,
        FavoriteProductMapper $favoriteProductMapper
    ) {
        $this->context = $context;
        $this->favoriteProductsRepository = $favoriteProductsRepository;
        $this->favoriteProductsCookieRepository = $favoriteProductsCookieRepository;
        $this->productRepository = $productRepository;
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
            $this->cachedFavoriteProducts = $this->favoriteProductsCookieRepository->getFavoriteProducts((int) $this->context->shop->id);
        }

        return $this->cachedFavoriteProducts;
    }

    public function getFavoriteProductForListing(int $page = 1, int $limit = 10, string $orderBy = 'date_add', string $orderWay = 'DESC'): array
    {
        if (!$this->isCustomerLogged()) {
            $favoriteProducts = $this->getFavoriteProducts();

            $count = count($favoriteProducts);

            if ($count <= ($page - 1) * $limit) {
                $page = 1 + (int) ($count / $limit);
            }

            $products = [];

            foreach ($favoriteProducts as $favoriteProduct) {
                $product['date_add'] = $favoriteProduct->getDateAdd();
                $product['id_product'] = $favoriteProduct->getIdProduct();
                $product['id_product_attribute'] = $favoriteProduct->getIdProductAttribute();

                $products[] = $product;
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
            $favoriteProducts = $this->favoriteProductsRepository->getFavoriteProductsForListing(
                (int) $this->context->customer->id,
                (int) $this->context->shop->id,
                (int) $this->context->language->id,
                $page,
                $limit,
                $orderBy,
                $orderWay
            );

            var_dump($favoriteProducts);
            die();

            return [
                'items' => $products,
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
}

