<?php

namespace Controllers;

use Inc\Utils;
use Models\ProductManager;
use stdClass;

class ProductsController {
    public static function index() {
        try {
            echo json_encode(['data' => ProductManager::allProducts()]);
        } catch(\Exception $e) {
            http_response_code(500);
            echo json_encode(['message' => 'failed', 'data' => $e->getMessage()]);
        }

    }
    
    public static function store() {
        try {
            $data = json_decode(file_get_contents("php://input"));
            $attrs = new stdClass();
            $attrs->weight = $data->weight;
            $productManager = new ProductManager(
                $data->sku,
                $data->name,
                $data->price,
                Utils::onlyFirstCharacterIsCapital($data->type),
                $attrs);
            
            $product = $productManager->saveProduct();
      
            if($product === false) throw new \Exception('Failed to save the product.');
            
            http_response_code(200);
            echo json_encode(['data' => $product]);

        } catch(\Exception $e) {
            http_response_code(500);
            echo json_encode(['message' => 'failed', 'data' => $e->getMessage()]);            
        }
    }
}