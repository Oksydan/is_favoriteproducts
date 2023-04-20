<?php

declare(strict_types=1);

namespace Oksydan\IsFavoriteProducts\Repository;

use Oksydan\IsFavoriteProducts\DTO\FavoriteProduct;
use Symfony\Component\HttpFoundation\Cookie;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class FavoriteProductCookieRepository
{
    const COOKIE_NAME = 'favorite_products';
    const DATE_FORMAT = 'Y-m-d H:i:s';

    /**
     * @var Response
     */
    private $response;

    /**
     * @var array[FavoriteProduct]
     */
    protected $favoriteProducts = null;

    public function __construct()
    {
        $this->response = new Response();
    }

    /*
     * @return array[FavoriteProduct]
     */
    public function getFavoriteProducts($idShop): array
    {
        if (!is_null($this->favoriteProducts)) {
            return $this->favoriteProducts;
        }

        $products = [];
        $cookies = Request::createFromGlobals()->cookies;
        $favoriteProducts = $cookies->get(self::COOKIE_NAME);

        if (empty($favoriteProducts)) {
            return $products;
        }

        $cookieProductsRaw = json_decode($favoriteProducts, true);

        foreach ($cookieProductsRaw as $cookieProductRaw) {
            if ($cookieProductRaw['id_shop'] !== $idShop) {
                continue;
            }

            $product = new FavoriteProduct();
            $product->setIdProduct($cookieProductRaw['id_product']);
            $product->setIdProductAttribute($cookieProductRaw['id_product_attribute']);
            $product->setDateAdd(\DateTimeImmutable::createFromFormat(self::DATE_FORMAT, $cookieProductRaw['date_add']));
            $product->setIdShop($idShop);

            $products[] = $product;
        }

        return $products;
    }

    public function addFavoriteProduct(FavoriteProduct $favoriteProduct): void
    {
        $favoriteProducts = $this->getFavoriteProducts($favoriteProduct->getIdShop());
        $favoriteProducts[] = $favoriteProduct;

        $this->setFavoriteProducts($favoriteProducts);
    }

    public function removeFavoriteProduct(FavoriteProduct $favoriteProduct): void
    {
        $favoriteProducts = $this->getFavoriteProducts($favoriteProduct->getIdShop());

        $favoriteProducts = array_filter($favoriteProducts, function ($product) use ($favoriteProduct) {
            return !($product->getIdProduct() == $favoriteProduct->getIdProduct() &&
                    $product->getIdProductAttribute() == $favoriteProduct->getIdProductAttribute() &&
                    $product->getIdShop() == $favoriteProduct->getIdShop());
        });

        $this->setFavoriteProducts($favoriteProducts);
    }

    public function setFavoriteProducts(array $favoriteProducts): void
    {
        $cookieProducts = [];

        foreach ($favoriteProducts as $favoriteProduct) {
            $cookieProducts[] = [
                'id_product' => $favoriteProduct->getIdProduct(),
                'id_product_attribute' => $favoriteProduct->getIdProductAttribute(),
                'date_add' => $favoriteProduct->getDateAdd()->format(self::DATE_FORMAT),
                'id_shop' => $favoriteProduct->getIdShop(),
            ];
        }

        $this->response->headers->setCookie(new Cookie(
            self::COOKIE_NAME,
            json_encode($cookieProducts),
            (new \DateTime('now'))->modify('+ 30 days')->getTimestamp()
        ));

        $this->response->sendHeaders();

        $this->favoriteProducts = $favoriteProducts;
    }

    public function isProductAlreadyInFavorites(FavoriteProduct $favoriteProductToCheck): bool
    {
        $favoriteProducts = $this->getFavoriteProducts($favoriteProductToCheck->getIdShop());

        if (empty($favoriteProducts)) {
            return false;
        }

        foreach ($favoriteProducts as $favoriteProduct) {
            if ($favoriteProduct->getIdProduct() === $favoriteProductToCheck->getIdProduct() &&
                $favoriteProduct->getIdProductAttribute() === $favoriteProductToCheck->getIdProductAttribute()) {
                return true;
            }
        }

        return false;
    }

    public function clearFavoriteProducts(): void
    {
        $this->response->headers->clearCookie(self::COOKIE_NAME);

        $this->response->sendHeaders();
    }
}
