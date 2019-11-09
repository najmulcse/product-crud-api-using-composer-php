<?php
namespace API\products;
// required headers
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");

// database connection will be here

// include database and object files
require_once '../../vendor/autoload.php';
// instantiate database and product object
use API\config\Database;
use API\object\Product;
use PDO;

$database = new Database();
$db = $database->getConnection();

// initialize object
$product = new Product($db);

// read products will be here


// query products
$stmt = $product->read();
$num = $stmt->rowCount();

// check if more than 0 record found

$response = array();
if($num>0){

    // products array
    $products_arr=array();

    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)){

        $product_item=array(
            "id" => $row['id'],
            "name" => $row['name'],
            "slug" => html_entity_decode($row['slug']),
            "price" => $row['price'],
        );

        array_push($products_arr, $product_item);
    }

    http_response_code(200);
    $response['data'] = $products_arr;
    $response['status'] = 'success';
    echo json_encode($response);
}else{

    http_response_code(200);
    $response['data'] = [];
    $response['status'] = 'success';
    echo json_encode($response);
}