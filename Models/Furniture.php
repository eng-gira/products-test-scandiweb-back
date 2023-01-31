<?php

namespace Models;

use Contracts\Arrayable;
use Data\DB;
use Enums\ProductType;

class Furniture extends Product implements Arrayable {
    protected string $h;
    protected string $w;
    protected string $l;


    public function setAttrs(object $attrs) {
        if(!isset($attrs->h, $attrs->l, $attrs->w)) throw new \Exception('Height, width and length were expected but not found.');
        if(!is_numeric($attrs->h) || !is_numeric($attrs->l) || !is_numeric($attrs->w)) throw new \Exception('Height, width and length should be numeric.');
        $this->setH($attrs->h);
        $this->setW($attrs->w);
        $this->setL($attrs->l);
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
            $attrs = implode(',', [$this->getH(), $this->getW(), $this->getL()]);

            $stmt->bind_param("sssss", $sku, $name, $price, $type, $attrs);

            if ($stmt->execute()) {
                $furniture = $this;
                $furniture->id = $conn->insert_id;

                return $furniture;
            }
        }

        throw new \Exception('Failed to save Furniture product.');
        return false;
    }

    public static function allAsArray(): ?array
    {
             // Proceed
             $conn = DB::connect();
             $type = ProductType::Furniture->name;
             $sql = "SELECT * FROM products WHERE type = '$type'";
             $res = $conn->query($sql);
             if ($res->num_rows != 0) {
                 $furnitures = [];
                 while ($row = $res->fetch_assoc()) {
                    $furniture = new Furniture();
                    $furniture->setId($row['id']);
                    $furniture->setSKU($row['sku']);
                    $furniture->setName($row['name']);
                    $furniture->setType($row['type']);
                    $furniture->setPrice($row['price']);
                    $attrsArr = explode(',', $row['attrs']);
                    $furniture->setH($attrsArr[0]);
                    $furniture->setW($attrsArr[1]);
                    $furniture->setL($attrsArr[2]);
                    array_push($furnitures, $furniture->toArray());
                 }
     
                 return $furnitures;
             } else {
                 return [];
             }
     
            throw new \Exception('Failed to get Furniture products.');
            return null;   
    }

    public function setH($h) { $this->h = $h; }
    public function setW($w) { $this->w = $w; }
    public function setL($l) { $this->l = $l; }
    public function getH(): string { return $this->h; }
    public function getW(): string { return $this->w; }
    public function getL(): string { return $this->l; }

    public function toArray(): array
    {
        return [
            'id' => $this->getId(),
            'sku' => $this->getSKU(),
            'name' => $this->getName(),
            'price' => $this->getPrice(),
            'type' => $this->getType(),
            'h' => $this->getH(),
            'w' => $this->getW(),
            'l' => $this->getL(),
        ];
    }
}