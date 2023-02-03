<?php

namespace Models;

use Contracts\Arrayable;
use Data\DB;
use stdClass;

class Furniture extends Product implements Arrayable {
    protected string $height;
    protected string $width;
    protected string $length;

    public function setAttrs(string|object $attrs) {
        if(!is_object($attrs)) {
            $attrsObject = new stdClass();
            $attrsArr = explode(',', $attrs);
            
            if(count($attrsArr) < 3) 
            {
                throw new \Exception('Height, width and length were expected but not found.');
            }

            $attrsObject->height = $attrsArr[0];
            $attrsObject->width = $attrsArr[1];
            $attrsObject->length = $attrsArr[2];
        } else {
            $attrsObject = $attrs;
            if(!isset($attrsObject->height, $attrsObject->length, $attrsObject->width)) 
            {
                throw new \Exception('Height, width and length were expected but not found.');
            }
        }
        if(!is_numeric($attrsObject->height) || !is_numeric($attrsObject->length) || !is_numeric($attrsObject->width)) 
            throw new \Exception('Height, width and length should be numeric.');

        $this->setHeight($attrsObject->height);
        $this->setWidth($attrsObject->width);
        $this->setLength($attrsObject->length);
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
            $attrs = implode(',', [$this->getHeight(), $this->getWidth(), $this->getLength()]);

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

    public function setHeight($height) { $this->height = $height; }
    public function setWidth($width) { $this->width = $width; }
    public function setLength($length) { $this->length = $length; }
    public function getHeight(): string { return $this->height; }
    public function getWidth(): string { return $this->width; }
    public function getLength(): string { return $this->length; }

    public function toArray(): array
    {
        return [
            'id' => $this->getId(),
            'sku' => $this->getSku(),
            'name' => $this->getName(),
            'price' => $this->getPrice(),
            'type' => $this->getType(),
            'height' => $this->getHeight(),
            'width' => $this->getWidth(),
            'length' => $this->getLength(),
        ];
    }
}