<?php

namespace Controllers;

use Models\ProductManager;
use stdClass;

class ProductsController {
    public static function index() {
        try {
            echo json_encode(['data' => []]);
        } catch(\Exception $e) {
            http_response_code(500);
            echo json_encode(['message' => 'failed', 'data' => $e->getMessage()]);
        }

    }
    
    public static function store() {
        try {
            // Testing
            $attrs = new stdClass();
            $attrs->l = 3;
            $attrs->w = 2;
            $attrs->h = 1;
            $productManager = new ProductManager('bbccdd', 'NewProd', 1.5, 'Furniture', $attrs);
            var_dump($productManager);
            $productManager->saveProduct();
        } catch(\Exception $e) {
            http_response_code(500);
            echo json_encode(['message' => 'failed', 'data' => $e->getMessage()]);            
        }
    }
}