<?php

declare(strict_types=1);

namespace Oksydan\IsFavoriteProducts\DTO;

class FavoriteProduct
{
    /**
     * @var int
     */
    private $id_product;

    /**
     * @var int
     */
    private $id_product_attribute;

    /**
     * @var int
     */
    private $id_customer = 0;

    /**
     * @var int
     */
    private $id_shop;

    /**
     * @var \DateTimeImmutable
     */
    private $date_add;

    public function getIdProduct(): int
    {
        return $this->id_product;
    }

    public function setIdProduct(int $id_product): self
    {
        $this->id_product = $id_product;

        return $this;
    }

    public function getIdProductAttribute(): int
    {
        return $this->id_product_attribute;
    }

    public function setIdProductAttribute(int $id_product_attribute): self
    {
        $this->id_product_attribute = $id_product_attribute;

        return $this;
    }

    public function getDateAdd(): \DateTimeImmutable
    {
        return $this->date_add;
    }

    public function setDateAdd(\DateTimeImmutable $date_add): self
    {
        $this->date_add = $date_add;

        return $this;
    }

    public function getIdCustomer(): int
    {
        return $this->id_customer;
    }

    public function setIdCustomer(int $id_customer): self
    {
        $this->id_customer = $id_customer;

        return $this;
    }

    public function getIdShop(): int
    {
        return $this->id_shop;
    }

    public function setIdShop(int $id_shop): self
    {
        $this->id_shop = $id_shop;

        return $this;
    }
}
