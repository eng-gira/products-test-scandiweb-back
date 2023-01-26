<?php

namespace Models;

use Data\DB;

class Book extends Product {
    protected int $weight;

    public function __construct($sku, $name, $price, $weight)
    {
        $this->sku = $sku;
        $this->name = $name;
        $this->price = $price;
        $this->weight = $weight;
        $this->type = 'Book';
    }

    public function save(): Product|false {
        
        if($this->skuExists($this->sku)) {
            throw new \Exception('SKU already exists.');
            return false;
        }
        if(!is_numeric($this->weight)) {
            throw new \Exception('Weight should be numeric.');
            return false;
        }

        // Proceed
        $conn = DB::connect();
        $sql = "INSERT INTO products (sku, name, price, type, weight) VALUES (?, ?, ?, ?, ?)";
        if ($stmt = $conn->prepare($sql)) {
            $stmt->bind_param("ssdsd", $this->sku, $this->name, $this->price, $this->type, $this->weight);
            if ($stmt->execute()) {
                $book = $this;
                $book->id = $conn->insert_id;

                return $book;
            }
        }

        throw new \Exception('Failed to saved product.');
        return false;
    }

    public static function all(): array|false {
        return false;
    }
   
    public static function find(int $id): Product|false {
        return false;
    }

    public function delete(): bool {
        return false;
    }
    
    private static function skuExists($sku): bool {
        $allProducts = self::all();
        if ($allProducts !== false) {
            $existingSKUs = array_map(function ($product) {
                return $product->sku;
            }, $allProducts);
        }
        if ($allProducts !== false && in_array($sku, $existingSKUs)) {
            return true;
        }
        return false;
    }
}