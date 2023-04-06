<?php

namespace Oksydan\IsFavoriteProducts\Mapper;

use Oksydan\IsFavoriteProducts\DTO\FavoriteProduct as FavoriteProductDTO;
use Oksydan\IsFavoriteProducts\Entity\FavoriteProduct;

class FavoriteProductMapper
{
    public function mapFavoriteProductDTOToFavoriteProductEntity(FavoriteProductDTO $favoriteProductDTO): FavoriteProduct
    {
        $favoriteProduct = new FavoriteProduct();
        $favoriteProduct->setIdProduct($favoriteProductDTO->getIdProduct());
        $favoriteProduct->setIdProductAttribute($favoriteProductDTO->getIdProductAttribute());
        $favoriteProduct->setIdCustomer($favoriteProductDTO->getIdCustomer());
        $favoriteProduct->setIdShop($favoriteProductDTO->getIdShop());
        $favoriteProduct->setDateAdd($favoriteProductDTO->getDateAdd());

        return $favoriteProduct;
    }

    public function mapFavoriteProductEntityToFavoriteProductDTO(FavoriteProduct $favoriteProduct): FavoriteProductDTO
    {
        $favoriteProductDTO = new FavoriteProductDTO();
        $favoriteProductDTO->setIdProduct($favoriteProduct->getIdProduct());
        $favoriteProductDTO->setIdProductAttribute($favoriteProduct->getIdProductAttribute());
        $favoriteProductDTO->setIdCustomer($favoriteProduct->getIdCustomer());
        $favoriteProductDTO->setIdShop($favoriteProduct->getIdShop());
        $favoriteProductDTO->setDateAdd($favoriteProduct->getDateAdd());

        return $favoriteProductDTO;
    }
}
