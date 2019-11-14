<?php
// required headers
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: access");
header("Access-Control-Allow-Methods: GET");
header("Access-Control-Allow-Credentials: true");
header('Content-Type: application/json');

use API\config\Database;
use API\object\Product;

// include database and object files
require_once '../../vendor/autoload.php';
// get database connection
$database = new Database();
$db = $database->getConnection();

// prepare product object
$product = new Product($db);

// set ID property of record to read
$product->id = isset($_GET['id']) ? $_GET['id'] : die();

// read the details of product to be edited
$product->readOne();

if($product->name!=null){
    // create array
    $product_arr = array(
        "id" =>  $product->id,
        "name" => $product->name,
        "description" => $product->description,
        "price" => $product->price,
        "slug" => $product->slug

    );

    // set response code - 200 OK
    http_response_code(200);
    $response['data'] = $product_arr;
    $response['status'] = 'success';
    echo json_encode($response);
}

else{
    // set response code - 404 Not found
    http_response_code(404);

    $response['data'] = [];
    $response['status'] = 'error';
    echo json_encode($response);
}
?>