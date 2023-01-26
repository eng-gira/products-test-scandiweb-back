<?php

namespace Factories;

use Models\Book;
use Models\DVD;
use Models\Furniture;
use Models\Product;

class ProductFactory {
    public static function createProduct($sku, $name, $price, $type): Product {
        if($type=="Book") return new Book($sku, $name, $price, 0);
        elseif($type=="Furniture") return new Furniture($sku, $name, $price, 0, 0, 0);
        elseif($type=="DVD") return new DVD($sku, $name, $price, 0);
        else return null;
    }
}