<?php

namespace awe;

abstract class ShopProductWriter
{
    protected array $products = [];

    public function addProduct(ShopProduct $shopProduct): void
    {
        $this->products[] = $shopProduct;
    }

    public function setProducts(array $products): void
    {
        $this->products = $products;
    }

    abstract public function write();
}