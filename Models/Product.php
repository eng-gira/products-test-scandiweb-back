<?php

namespace Models;

use Contracts\Arrayable;
use Data\DB;
use Factories\ProductFactory;

abstract class Product implements Arrayable
{
    protected int $id;
    protected string $sku;
    protected string $name;
    protected string $price;
    protected string $type;

    public function save(): Product|false
    {
        return false;
    }

    public static function all(): array|false
    {
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

                array_push($products, $product);
            }

            return $products;
        } else {
            return [];
        }

        throw new \Exception('Failed to get all products.');
        return false;
    }

    public static function delete(int $id): bool
    {
        $conn = DB::connect();
        $query = 'DELETE FROM products WHERE id = ?';
        if ($stmt=$conn->prepare($query)) {
            $stmt->bind_param('i', $id);
            return $stmt->execute();
        }

        return false;
    }

    public function toArray(): array
    {
        return [];
    }

    protected static function failIfSkuExists(string $sku)
    {
        $conn = DB::connect();
        $query = 'SELECT * FROM products WHERE sku = ?';
        if ($stmt=$conn->prepare($query)) {
            $stmt->bind_param('s', $sku);
            if ($stmt->execute()) {
                $stmt->store_result();
                if ($stmt->num_rows != 0) {
                    throw new \Exception('SKU already exists.');
                }
            }
        }
    }

    // Setters
    public function setId(int $id)
    {
        $this->id = $id;
    }
    public function setSku(string $sku)
    {
        $this->sku = $sku;
    }
    public function setName(string $name)
    {
        $this->name = $name;
    }
    public function setPrice(string $price)
    {
        $this->price = $price;
    }
    public function setType(string $type)
    {
        $this->type = $type;
    }
    public function setAttrs(string|object $attrs)
    {
    }

    // Getters
    public function getId(): int
    {
        return $this->id;
    }
    public function getSku(): string
    {
        return $this->sku;
    }
    public function getName(): string
    {
        return $this->name;
    }
    public function getPrice(): string
    {
        return $this->price;
    }
    public function getType(): string
    {
        return $this->type;
    }
}
