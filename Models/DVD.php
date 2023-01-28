<?php

namespace Models;


class DVD extends Product {
    protected float $size;

    public function setAttrs(object $attrs) {
        if(!isset($attrs->size)) throw new \Exception('Size was expected but not found.');
        if(!is_numeric($attrs->size)) throw new \Exception('Size should be numeric.');

        $this->size = floatval($attrs->size);
    }

    public function save(): Product|false {
        // Test
        echo 'Trying to save DVD.';
        return false;
    }
}