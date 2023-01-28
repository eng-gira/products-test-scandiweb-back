<?php

namespace Models;


class ProductManager {
    private Product $product;

    public function __construct($sku, $name, float $price, $type, object $attrs) {
        if($type=='Furniture') $this->product = new Furniture;
        elseif($type=='Book') $this->product = new Book;
        elseif($type=='DVD') $this->product = new DVD;
        else throw new \Exception('Failed to initialize Product. Wrong type passed (' . $type .').');

        $this->product->setSKU($sku);
        $this->product->setName($name);
        $this->product->setPrice($price);
        $this->product->setType($type);
        $this->product->setAttrs($attrs);
    }

    public function deleteProductsArray(array $ids) {
        foreach($ids as $id) $this->product->delete($id);
    }

    public function saveProduct(): Product|false {
        // Check if sku exists
        $allProducts = $this->allProducts();
        if ($allProducts !== false) {
            $existingSKUs = array_map(function ($product) {
                return $product->getSKU();
            }, $allProducts);
        }
        if ($allProducts !== false && in_array($this->product->getSKU(), $existingSKUs)) {
            throw new \Exception('SKU already exists.');
        }

        return $this->product->save();
    }

    public function allProducts(): array|false {
        $furnitureProducts = Furniture::all() ?? [];
        $bookProducts = Book::all() ?? [];
        $dvdProducts = DVD::all() ?? [];

        return [...$furnitureProducts, ...$bookProducts, ...$dvdProducts];
    }

    /**
     * @param string $type Book or DVD or Furniture
     */
    public function setProductType(string $type) {
        if($type != 'Book' && $type != 'DVD' && $type != 'Furniture')
            throw new \Exception('Failed to instantiate product. Wrong type passed (' . $type . ').');
        $this->product = new $type;
    }
}