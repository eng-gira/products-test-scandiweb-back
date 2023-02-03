<?php

namespace Factories;

use Enums\ProductType;
use Models\Book;
use Models\Dvd;
use Models\Furniture;
use Models\Product;

class ProductFactory {
    public static function factory(?int $id, string $sku, string $name, string $price, string $type, object|string $attrs): Product {
        if($type==ProductType::Furniture->name) $product = new Furniture;
        elseif($type==ProductType::Book->name) $product = new Book;
        elseif($type==ProductType::Dvd->name) $product = new Dvd;
        else throw new \Exception('Failed to initialize Product. Wrong type passed (' . $type .').');

        if($id !== null) $product->setId($id);

        $product->setSku($sku);
        $product->setSku($sku);
        $product->setName($name);
        $product->setPrice($price);
        $product->setType($type);
        $product->setAttrs($attrs);

        return $product;
    }
}