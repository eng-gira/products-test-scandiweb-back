<?php

namespace Models;

use Enums\ProductType;

class ProductManager {
    private Product $product;

    public function __construct($sku, $name, string $price, $type, object $attrs) {
        if($type==ProductType::Furniture->name) $this->product = new Furniture;
        elseif($type==ProductType::Book->name) $this->product = new Book;
        elseif($type==ProductType::Dvd->name) $this->product = new Dvd;
        else throw new \Exception('Failed to initialize Product. Wrong type passed (' . $type .').');

        $this->product->setSKU($sku);
        $this->product->setName($name);
        $this->product->setPrice($price);
        $this->product->setType($type);
        $this->product->setAttrs($attrs);
    }

    public function deleteMultipleProducts(array $ids) {
        foreach($ids as $id) $this->product->delete($id);
    }

    /**
     * @return array|false Return inserted Product as associative array on success, and false on failure.
     */
    public function saveProduct(): array|false {
        // Check if sku exists
        $allProducts = $this->allProducts();
        if ($allProducts !== false) {
            $existingSKUs = array_map(function ($product) {
                return $product['sku'];
            }, $allProducts);
        }
        if ($allProducts !== false && in_array($this->product->getSKU(), $existingSKUs)) {
            throw new \Exception('SKU already exists.');
        }

        return $this->product->save()->toArray();
    }

    public static function allProducts(): array {
        $furnitureProducts = Furniture::allAsArray() ?? [];
        $bookProducts = Book::allAsArray() ?? [];
        $dvdProducts = Dvd::allAsArray() ?? [];

        return [...$furnitureProducts, ...$bookProducts, ...$dvdProducts];
    }
}