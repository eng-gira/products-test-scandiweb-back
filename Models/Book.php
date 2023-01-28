<?php

namespace Models;

use Data\DB;

class Book extends Product {
    protected float $weight;

    public function setAttrs(object $attrs) {
        if(!isset($attrs->weight)) throw new \Exception('Weight was expected but not found.');
        if(!is_numeric($attrs->weight)) throw new \Exception('Weight should be numeric.');
        $this->weight = floatval($attrs->weight);
    }

    public function save(): Product|false {
        // Testing
        echo 'trying to save book...';
        return false;

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

    public static function all(): array|null {
        return null;
    }
   
    public static function find(int $id): Product|false {
        return false;
    }
}