<?php

namespace Facades;

use Models\Product;

class ProductFacade {
    public static function deleteMultipleProducts(array $ids) {
        foreach($ids as $id)
        {
            if(!Product::delete($id)) return false;
        }
        return true;
    }

    /**
     * @return Product|false Return inserted Product.
     */
    public static function saveProduct(Product $product): Product|false {
        // Check if sku exists
        $allProducts = self::allProducts();
        if ($allProducts !== false) {
            $existingSKUs = array_map(function ($product) {
                return $product['sku'];
            }, $allProducts);
        }
        if ($allProducts !== false && in_array($product->getSKU(), $existingSKUs)) {
            throw new \Exception('SKU already exists.');
        }

        return $product->save();
    }

    public static function allProducts(): array {
        return Product::allAsArray();
    }
}