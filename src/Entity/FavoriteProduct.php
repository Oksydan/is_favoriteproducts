<?php

declare(strict_types=1);

namespace Oksydan\IsFavoriteProducts\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="Oksydan\IsFavoriteProducts\Repository\FavoriteProductRepository")
 * @ORM\Table()
 */
class FavoriteProduct
{
    /**
     * @var int
     *
     * @ORM\Id
     * @ORM\Column(name="id_favorite_product", type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var int
     *
     * @ORM\Column(name="id_product", type="integer")
     */
    private $id_product;

    /**
     * @var int
     *
     * @ORM\Column(name="id_product_attribute", type="integer")
     */
    private $id_product_attribute;

    /**
     * @var int
     *
     * @ORM\Column(name="id_customer", type="integer")
     */
    private $id_customer;

    /**
     * @var int
     *
     * @ORM\Column(name="id_shop", type="integer")
     */
    private $id_shop;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="date_add", type="datetime_immutable")
     */
    private $date_add;

    public function __construct()
    {
        $this->date_add = new \DateTimeImmutable();
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function setId(int $id): self
    {
        $this->id = $id;

        return $this;
    }

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

    public function getDateAdd(): \DateTimeImmutable
    {
        return $this->date_add;
    }

    public function setDateAdd(\DateTimeImmutable $date_add): self
    {
        $this->date_add = $date_add;

        return $this;
    }
}
