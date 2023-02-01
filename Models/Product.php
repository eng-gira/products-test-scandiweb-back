<?php

namespace Models;

use Contracts\Arrayable;
use Data\DB;

abstract class Product implements Arrayable {
    protected int $id;
    protected string $sku;
    protected string $name;
    protected string $price;
    protected string $type;

    public function save(): Product|false {
        return false;
    }
    public static function find(int $id): Product|false {
        return false;
    }
   
    public static function allAsArray(): array|null {
        return null;
    }

    public static function delete(int $id): bool {
        /**
         * @todo delete single product identified by the PRIMARY_KEY $id.
         */
        $conn = DB::connect();
        $query = 'DELETE FROM products WHERE id = ?';
        if($stmt=$conn->prepare($query)) {
            $stmt->bind_param('i', $id);
            return $stmt->execute();
        }

        return false;
    }

    public function toArray(): array {
        return [];
    }
    
    // Setters
    public function setId(int $id) {
        $this->id = $id;
    }
    public function setSKU(string $sku) {
        $this->sku = $sku;
    }
    public function setName(string $name) {
        $this->name = $name;
    }
    public function setPrice(string $price) {
        $this->price = $price;
    }
    public function setType(string $type) {
        $this->type = $type;
    }
    public function setAttrs(object $attrs) {}

    // Getters
    public function getId(): int {
        return $this->id;
    }
    public function getSKU(): string {
        return $this->sku;
    }
    public function getName(): string {
        return $this->name;
    }
    public function getPrice(): string {
        return $this->price;
    }
    public function getType(): string {
        return $this->type;
    }
}