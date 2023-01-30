<?php

namespace Models;

use Contracts\Arrayable;

class DVD extends Product implements Arrayable {
    protected string $size;

    public function setAttrs(object $attrs) {
        if(!isset($attrs->size)) throw new \Exception('Size was expected but not found.');
        if(!is_numeric($attrs->size)) throw new \Exception('Size should be numeric.');

        $this->size = $attrs->size;
    }

    public function save(): Product|false {
        // Test
        echo 'Trying to save DVD.';
        return false;
    }

    public function getSize(): string {
        return $this->size;
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