<?php

namespace App;

use Countable;

class Basket implements Countable
{
    /**
     * @var array<Product>
     */
    private array $items = [];
    private int $vatPercentage;

    public function __construct(int $vatPercentage)
    {
        $this->vatPercentage = $vatPercentage;
    }

    public function add(Product $product)
    {
        $this->items[] = $product;
    }

    public function count(): int
    {
        return count($this->items);
    }

    public function getSubTotalInCents(): int
    {
        return array_sum(
            array_map(fn(Product $product) => $product->costInCents, $this->items)
        );
    }

    public function getVatInCents(): int
    {
        return $this->getSubTotalInCents() * $this->vatPercentage / 100;
    }

    public function getDeliveryCost(): int
    {
        if ($this->getSubTotalInCents() < 1000) {
            return 300;
        }

        return 200;
    }

    public function getTotalInCents(): int
    {
        return $this->getSubTotalInCents() + $this->getVatInCents() + $this->getDeliveryCost();
    }
}