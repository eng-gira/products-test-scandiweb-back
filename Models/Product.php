<?php

namespace Models;


abstract class Product {
    protected int $id;
    protected string $sku;
    protected string $name;
    protected float $price;
    protected string $type;

    public function save(): Product|false {
        return false;
    }
    public static function find(int $id): Product|false {
        return false;
    }
   
    public static function all(): array|null {
        return null;
    }
    public function delete(array $ids): bool {
        /**
         * @todo implement deletion here.
         */
        foreach($ids as $id) {

        }
        return false;
    }
    
    // Setters
    public function setSKU(string $sku) {
        $this->sku = $sku;
    }
    public function setName(string $name) {
        $this->name = $name;
    }
    public function setPrice(float $price) {
        $this->price = $price;
    }
    public function setType(string $type) {
        $this->type = $type;
    }
    public function setAttrs(object $attrs) {}

    // Getters
    public function getSKU(): string {
        return $this->sku;
    }
    public function getName(): string {
        return $this->name;
    }
    public function getPrice(): float {
        return $this->price;
    }
    public function getType(): string {
        return $this->type;
    }
}