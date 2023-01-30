<?php

namespace Models;

use Contracts\Arrayable;
use Data\DB;
use Enums\ProductType;

class Book extends Product implements Arrayable {
    protected string $weight;

    public function setAttrs(object $attrs) {
        if(!isset($attrs->weight)) throw new \Exception('Weight was expected but not found.');
        if(!is_numeric($attrs->weight)) throw new \Exception('Weight should be numeric.');
        $this->setWeight($attrs->weight);
    }

    public function save(): Product|false {
        // Proceed
        $conn = DB::connect();
        $sql = "INSERT INTO products (sku, name, price, type, attrs) VALUES (?, ?, ?, ?, ?)";
        if ($stmt = $conn->prepare($sql)) {
            
            $sku = $this->getSKU();
            $name = $this->getName();
            $price = $this->getPrice();
            $type = $this->getType();
            $attrs = $this->getWeight();

            $stmt->bind_param("sssss", $sku, $name, $price, $type, $attrs);

            if ($stmt->execute()) {
                $book = $this;
                $book->id = $conn->insert_id;

                return $book;
            }
        }

        throw new \Exception('Failed to saved book.');
        return false;
    }

    public static function allAsArray(): array|null {
        // Proceed
        $conn = DB::connect();
        $type = ProductType::Book->name;
        $sql = "SELECT * FROM products WHERE type = '$type'";
        $res = $conn->query($sql);
        if ($res->num_rows != 0) {
            $books = [];
            while ($row = $res->fetch_assoc()) {
                $book = new Book();
                $book->setId($row['id']);
                $book->setSKU($row['sku']);
                $book->setName($row['name']);
                $book->setType($row['type']);
                $book->setPrice($row['price']);
                $book->setWeight($row['attrs']);
                array_push($books, $book->toArray());
            }

            return $books;
        } else {
            return [];
        }

        throw new \Exception('Failed to get books.');
        return null;
    }
    public function getWeight(): string {
        return $this->weight;
    }
    public function setWeight($weight) { $this->weight = $weight; }

    public function toArray(): array
    {
        return [
            'id' => $this->getId(),
            'sku' => $this->getSKU(),
            'name' => $this->getName(),
            'price' => $this->getPrice(),
            'type' => $this->getType(),
            'weight' => $this->getWeight(),
        ];
    }
}