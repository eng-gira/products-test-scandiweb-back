<?php

namespace Controllers;

use Factories\ProductFactory;

class ProductsController {
    public static function index() {
        try {
            return json_encode(['data' => []]);
        } catch(\Exception $e) {
            http_response_code(500);
            return json_encode(['message' => 'failed', 'data' => $e->getMessage()]);
        }

    }
    
    public static function store() {
        $product = ProductFactory::createProduct('bbccd', 'FirstProd', 3, 'Book');

    }

    public static function show($id) {
        echo "At show... id: $id";
    }
}