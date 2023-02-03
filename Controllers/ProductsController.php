<?php

namespace Controllers;

use Facades\ProductFacade;
use Factories\ProductFactory;
use Inc\Utils;
use stdClass;

class ProductsController {
    public static function index() {
        try {
            echo json_encode(['data' => ProductFacade::allProducts()]);
        } catch(\Exception $e) {
            http_response_code(500);
            echo json_encode(['message' => 'failed', 'data' => $e->getMessage()]);
        }

    }
    
    public static function store() {
        try {
            $data = json_decode(file_get_contents("php://input"));
            $product = ProductFactory::factory(
                null,
                $data->sku,
                $data->name,
                $data->price,
                Utils::onlyFirstCharacterIsCapital($data->type),
                $data->attrs);
            
            ProductFacade::saveProduct($product);
      
            if($product === false) throw new \Exception('Failed to save the product.');
            
            http_response_code(200);
            echo json_encode(['data' => $product->toArray()]);

        } catch(\Exception $e) {
            http_response_code(500);
            echo json_encode(['message' => 'failed', 'data' => $e->getMessage()]);            
        }
    }

    public static function massDelete() {
        try{
            $data = json_decode(file_get_contents("php://input"));
            if(ProductFacade::deleteMultipleProducts($data)) {
                http_response_code(200);
                echo json_encode(['message' => 'success', 'data' => 'Product(s) deleted successfully.']);
            } 
            else throw new \Exception('Delete attempt failed.');
        }
        catch(\Exception $e) {
            http_response_code(500);
            echo json_encode(['message' => 'failed', 'data' => $e->getMessage()]);
        }
    }
}