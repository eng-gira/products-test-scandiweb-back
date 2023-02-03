<?php

namespace Controllers;

use Factories\ProductFactory;
use Inc\Utils;
use Models\Product;

class ProductsController {
    public static function index() {
        try {
            $products = Product::all();

            $productsJsonable = array_map(function ($product) {
                return $product->toArray();
            }, $products);
            
            echo json_encode(['data' => $productsJsonable]);

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
            
            if($product->save() === false) throw new \Exception('Failed to save the product.');
            
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
            
            foreach($data as $id) {
                if(!Product::delete($id))
                {
                    throw new \Exception('Delete attempt failed.');
                }
            }

            http_response_code(200);
            echo json_encode(['message' => 'success', 'data' => 'Product(s) deleted successfully.']);
        }
        catch(\Exception $e) {
            http_response_code(500);
            echo json_encode(['message' => 'failed', 'data' => $e->getMessage()]);
        }
    }
}