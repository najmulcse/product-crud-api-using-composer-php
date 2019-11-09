<?php
// required headers
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

use API\config\Database;
use API\object\Product;

// include database and object files
require_once __DIR__ . '../../vendor/autoload.php';

// get database connection
$database = new Database();
$db = $database->getConnection();

// prepare product object
$product = new Product($db);

// get id of product to be edited
$data = json_decode(file_get_contents("php://input"));

// set ID property of product to be edited
$product->id = $data->id;

// set product property values
$product->name = $data->name;
$product->price = $data->price;
$product->slug = $data->slug;
$product->description = $data->description;

// update the product
$response = array();
if($product->update()){

    // set response code - 200 ok
    http_response_code(200);

    $message = "Product updated successfully.";
    $response['message'] = $message;
    $response['status'] = 'success';

    echo json_encode($response);
}

else{

    // set response code - 503 service unavailable
    http_response_code(503);
    $message = "Unable to update products.";
    $response['message'] = $message;
    $response['status'] = 'error';

    echo json_encode($response);
}
?>