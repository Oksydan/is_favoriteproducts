<?php

namespace Oksydan\IsFavoriteProducts\Collection;

use Oksydan\IsFavoriteProducts\DTO\StatFavoriteProduct;

class StatsFavoriteProductCollection implements \IteratorAggregate
{
    /** @var StatFavoriteProduct[] */
    private array $products = [];

    public function add(StatFavoriteProduct $product): void
    {
        $this->products[] = $product;
    }

    public function getIterator(): \Traversable
    {
        return new \ArrayIterator($this->products);
    }

    public function toArray(): array
    {
        return $this->products;
    }
}
