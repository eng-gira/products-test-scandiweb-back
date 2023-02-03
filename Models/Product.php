<?php

namespace Models;

use Contracts\Arrayable;
use Data\DB;
use Facades\ProductFacade;
use Factories\ProductFactory;

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
   
    /**
     * @return array All as array to be converted to JSON for responses.
     */
    public static function allAsArray(): array|false {
        $conn = DB::connect();
        $sql = "SELECT * FROM products";
        $res = $conn->query($sql);
        if ($res->num_rows != 0) {
            $products = [];
            while ($row = $res->fetch_assoc()) {
                $product = ProductFactory::factory(
                    $row['id'],
                    $row['sku'],
                    $row['name'],
                    $row['price'],
                    $row['type'],
                    $row['attrs']
                );

                $productArr = $product->toArray();

                array_push($products, $productArr);
            }

            return $products;
        } else {
            return [];
        }

        throw new \Exception('Failed to get all products.');
        return false;
    }

    public static function delete(int $id): bool {
        $conn = DB::connect();
        $query = 'DELETE FROM products WHERE id = ?';
        if($stmt=$conn->prepare($query)) {
            $stmt->bind_param('i', $id);
            return $stmt->execute();
        }

        return false;
    }
    
    public static function orgAttrs(array $productAsArray): array {
        return [];
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
    public function setAttrs(string|object $attrs) {}

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