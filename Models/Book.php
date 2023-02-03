<?php

namespace Models;

use Data\DB;
use stdClass;

class Book extends Product {
    protected string $weight;

    public function setAttrs($attrs) {
        if(!is_object($attrs)) {
            $attrsObject = new stdClass();
            $attrsObject->weight = $attrs;
        } else {
            $attrsObject = $attrs;
        }

        if(!isset($attrsObject->weight)) throw new \Exception('Weight was expected but not found.');
        if(!is_numeric($attrsObject->weight)) throw new \Exception('Weight should be numeric.');
        $this->setWeight($attrsObject->weight);

    }

    public function save(): Product|false {
        parent::failIfSkuExists($this->getSku());

        // Proceed
        $conn = DB::connect();
        $sql = "INSERT INTO products (sku, name, price, type, attrs) VALUES (?, ?, ?, ?, ?)";
        if ($stmt = $conn->prepare($sql)) {
            
            $sku = $this->getSku();
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

        throw new \Exception('Failed to save Book product.');
        return false;
    }

    public function getWeight(): string {
        return $this->weight;
    }
    public function setWeight(string $weight) { $this->weight = $weight; }

    public function toArray(): array
    {
        return [
            'id' => $this->getId(),
            'sku' => $this->getSku(),
            'name' => $this->getName(),
            'price' => $this->getPrice(),
            'type' => $this->getType(),
            'weight' => $this->getWeight(),
        ];
    }
}