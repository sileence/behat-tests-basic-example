<?php

namespace App;

class ArrayProductRepository
{
    private array $items = [];

    public function getAll(): array
    {
        return $this->items;
    }

    public function save(Product $product): void
    {
        $this->items[] = $product;
    }

    /**
     * @throws ProductNotFoundException
     */
    public function getByTitle(string $productTitle): Product
    {
        foreach ($this->items as $product) {
            if ($product->title === $productTitle) {
                return $product;
            }
        }

        throw new ProductNotFoundException("The product title [{$productTitle}] was not found!");
    }
}