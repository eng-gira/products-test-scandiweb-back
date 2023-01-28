<?php

namespace Models;


class Furniture extends Product {
    protected float $h;
    protected float $w;
    protected float $l;


    public function setAttrs(object $attrs) {
        if(!isset($attrs->h, $attrs->l, $attrs->w)) throw new \Exception('Height, width and length were expected but not found.');
        if(!is_numeric($attrs->h) || !is_numeric($attrs->l) || !is_numeric($attrs->w)) throw new \Exception('Height, width and length were expected but not found.');
        $this->h = floatval($attrs->h);
        $this->w = floatval($attrs->w);
        $this->l = floatval($attrs->l);
    }

    public function save(): Product|false {
        // Test
        echo 'Trying to save Furniture.';
        return false;
    }
}