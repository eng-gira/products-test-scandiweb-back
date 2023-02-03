<?php

namespace Models;

use Data\DB;
use stdClass;

class Dvd extends Product
{
    protected string $size;

    public function setAttrs(string|object $attrs)
    {
        if (!is_object($attrs)) {
            $attrsObject = new stdClass();
            $attrsObject->size = $attrs;
        } else {
            $attrsObject = $attrs;
        }

        if (!isset($attrsObject->size)) {
            throw new \Exception('Size was expected but not found.');
        }
        if (!is_numeric($attrsObject->size)) {
            throw new \Exception('Size should be numeric.');
        }

        $this->setSize($attrsObject->size);
    }

    public function save(): Product|false
    {
        parent::failIfSkuExists($this->getSku());

        // Proceed
        $conn = DB::connect();
        $sql = "INSERT INTO products (sku, name, price, type, attrs) VALUES (?, ?, ?, ?, ?)";
        if ($stmt = $conn->prepare($sql)) {
            $sku = $this->getSku();
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

    public function getSize(): string
    {
        return $this->size;
    }
    public function setSize(string $size)
    {
        $this->size = $size;
    }

    public function toArray(): array
    {
        return [
            'id' => $this->getId(),
            'sku' => $this->getSku(),
            'name' => $this->getName(),
            'price' => $this->getPrice(),
            'type' => $this->getType(),
            'size' => $this->getSize(),
        ];
    }
}
