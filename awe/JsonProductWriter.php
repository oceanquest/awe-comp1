<?php

namespace awe;

class JsonProductWriter extends ShopProductWriter
{
    public function write(): void
    {
        $products = [];

        foreach ($this->products as $product) {
            $products[] = $this->addEachProductAsJSON($product);
        }

        echo json_encode($products, JSON_THROW_ON_ERROR);
    }

    private function addEachProductAsJSON(ShopProduct $product): array
    {
        $jsonProduct = [
            'id' => $product->getId(),
            'title' => $product->getTitle(),
            'firstname' => $product->getFirstName(),
            'mainname' => $product->getMainName(),
            'price' => $product->getPrice(),
        ];

        if ($product instanceof BookProduct) {
            $jsonProduct['numpages'] = $product->getNumberOfPages();
            $jsonProduct['type'] = 'book';
        } elseif ($product instanceof CdProduct) {
            $jsonProduct['playlength'] = $product->getPlayLength();
            $jsonProduct['type'] = 'cd';
        }

        return $jsonProduct;
    }
}