<?php
// required headers
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

// include database and object file
require_once __DIR__ . '../../vendor/autoload.php';
// get database connection
use API\config\Database;
use API\object\Product;

$database = new Database();
$db = $database->getConnection();

// prepare product object
$product = new Product($db);

// get product id
$data = json_decode(file_get_contents("php://input"));

// set product id to be deleted
$product->id = $data->id;

// delete the product
if($product->delete()){

    // set response code - 200 ok
    http_response_code(200);

    $message = "Product deleted successfully.";
    $response['message'] = $message;
    $response['status'] = 'success';

    echo json_encode($response);
}

// if unable to delete the product
else{

    // set response code - 503 service unavailable
    http_response_code(503);

    $message = "Unable to delete. Please try again";
    $response['message'] = $message;
    $response['status'] = 'error';

    echo json_encode($response);
}

?>