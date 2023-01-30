<?php

namespace Models;

use Contracts\Arrayable;

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
        // Test
        echo 'Trying to save Furniture.';
        return false;
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