<?php

declare(strict_types=1);

namespace Oksydan\IsFavoriteProducts\DTO;

class StatFavoriteProduct
{
    private int $idProduct;

    private int $idProductAttribute;

    private int $total;

    public function __construct(int $idProduct, int $idProductAttribute, int $total)
    {
        $this->idProduct = $idProduct;
        $this->idProductAttribute = $idProductAttribute;
        $this->total = $total;
    }

    public function getIdProduct(): int
    {
        return $this->idProduct;
    }

    public function getIdProductAttribute(): int
    {
        return $this->idProductAttribute;
    }

    public function getTotal(): int
    {
        return $this->total;
    }
}
