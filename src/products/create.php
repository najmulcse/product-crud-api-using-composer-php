<?php
// required headers
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

require_once '../../vendor/autoload.php';

use API\config\Database;
use API\object\Product;

$database = new Database();
$db = $database->getConnection();

$product = new Product($db);

// get posted data
$data = json_decode(file_get_contents("php://input"));

// make sure data is not empty
if(
    !empty($data->name) &&
    !empty($data->price) &&
    !empty($data->slug) &&
    !empty($data->description)
){

    // set product property values
    $product->name = $data->name;
    $product->price = $data->price;
    $product->slug = $data->slug;
    $product->description = $data->description;
    // create the product
    if($product->create()){

        // set response code - 201 created
        http_response_code(201);
        $message = "Product created successfully.";
        $response['message'] = $message;
        $response['status'] = 'success';

        echo json_encode($response);
    }

    // if unable to create the product, tell the user
    else{

        // set response code - 503 service unavailable
        http_response_code(503);
        $message = "Unable to create product.";
        $response['message'] = $message;
        $response['status'] = 'error';

        echo json_encode($response);
    }
}else{

    // set response code - 400 bad request
    http_response_code(400);
    $message = "Unable to create product. Need more required fields";
    $response['message'] = $message;
    $response['status'] = 'error';
    echo json_encode($response);
}

// create product
function create(){

    // query to insert record
    $query = "INSERT INTO
                " . $this->table_name . "
            SET
                name=:name, price=:price,slug=:slug, description=:description";

    // prepare query
    $stmt = $this->conn->prepare($query);

    // sanitize
    $this->name=htmlspecialchars(strip_tags($this->name));
    $this->price=htmlspecialchars(strip_tags($this->price));
    $this->description=htmlspecialchars(strip_tags($this->description));
    $this->slug=htmlspecialchars(strip_tags($this->slug));


    // bind values
    $stmt->bindParam(":name", $this->name);
    $stmt->bindParam(":price", $this->price);
    $stmt->bindParam(":description", $this->description);
    $stmt->bindParam(":slug", $this->slug);

    // execute query
    if($stmt->execute()){
        return true;
    }

    return false;

}
?>