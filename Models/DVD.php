<?php

namespace Models;

use Contracts\Arrayable;
use Data\DB;
use Enums\ProductType;
use stdClass;

class Dvd extends Product implements Arrayable {
    protected string $size;

    public function setAttrs(string|object $attrs) {
        if(!is_object($attrs)) {
            $attrsObject = new stdClass();
            $attrsObject->size = $attrs;
        } else {
            $attrsObject = $attrs;
        }

        if(!isset($attrsObject->size)) throw new \Exception('Size was expected but not found.');
        if(!is_numeric($attrsObject->size)) throw new \Exception('Size should be numeric.');

        $this->setSize($attrsObject->size);
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
            $attrs = $this->getSize();

            $stmt->bind_param("sssss", $sku, $name, $price, $type, $attrs);

            if ($stmt->execute()) {
                $dvd = $this;
                $dvd->id = $conn->insert_id;

                return $dvd;
            }
        }

        throw new \Exception('Failed to save DVD product.');
        return false;
    }

    // public static function allAsArray(): array|null {
    //     // Proceed
    //     $conn = DB::connect();
    //     $type = ProductType::Dvd->name;
    //     $sql = "SELECT * FROM products WHERE type = '$type'";
    //     $res = $conn->query($sql);
    //     if ($res->num_rows != 0) {
    //         $dvds = [];
    //         while ($row = $res->fetch_assoc()) {
    //             $dvd = new Dvd();
    //             $dvd->setId($row['id']);
    //             $dvd->setSKU($row['sku']);
    //             $dvd->setName($row['name']);
    //             $dvd->setType($row['type']);
    //             $dvd->setPrice($row['price']);
    //             $dvd->setSize($row['attrs']);
    //             array_push($dvds, $dvd->toArray());
    //         }

    //         return $dvds;
    //     } else {
    //         return [];
    //     }

    //     throw new \Exception('Failed to get DVD products.');
    //     return null;
    // }

    public function getSize(): string {
        return $this->size;
    }
    public function setSize(string $size) {
        $this->size = $size;
    }

    public function toArray(): array
    {
        return [
            'id' => $this->getId(),
            'sku' => $this->getSKU(),
            'name' => $this->getName(),
            'price' => $this->getPrice(),
            'type' => $this->getType(),
            'size' => $this->getSize(),
        ];
    }
}