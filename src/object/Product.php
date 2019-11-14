<?php
namespace API\object;

use API\config\Database;
use PDO;
use PDOException;

class Product{

    // database connection and table name
    private $conn;
    private $table_name = "products";

    // object properties
    public $id;
    public $name;
    public $description;
    public $price;
    public $slug;


    // constructor with $db as database connection
    public function __construct($db){
        $this->conn = $db;
    }

    // read products

    public function read(){

        // select all query
        $query = "SELECT * FROM " . $this->table_name;

        // prepare query statement
        $stmt = $this->conn->prepare($query);

        // execute query
        $stmt->execute();

        return $stmt;
    }
    // create product

    public function createTable()
    {
        $table = "products";
        try {
           
            $this->conn->setAttribute( PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION );//Error Handling
            $sql ="CREATE TABLE IF NOT EXISTS ".$this->table_name."(
            id INT( 11 ) AUTO_INCREMENT PRIMARY KEY,
            name VARCHAR( 250 ) NOT NULL,
            slug VARCHAR( 150 ) NOT NULL, 
            price VARCHAR( 150 ) NOT NULL, 
            description VARCHAR( 50 ) NOT NULL);" ;
            $this->conn->exec($sql);

        } catch(PDOException $e) {
            echo $e->getMessage();//Remove or change message in production code
        }
    }
    public function create(){

        $query = "SHOW TABLES LIKE " .$this->table_name;   
        $table = $this->conn->exec($query);
        if(!$table){
            $this->createTable();
        }
        // query to insert record
        $query = "INSERT INTO
                " . $this->table_name . "
            SET
                name=:name, price=:price, description=:description, slug=:slug";

        // prepare query
        $stmt = $this->conn->prepare($query);

        // sanitize
        $this->name=htmlspecialchars(strip_tags($this->name));
        $this->price=htmlspecialchars(strip_tags($this->price));
        $this->slug=htmlspecialchars(strip_tags($this->slug));
        $this->description=htmlspecialchars(strip_tags($this->description));

        // bind values
        $stmt->bindParam(":name", $this->name);
        $stmt->bindParam(":price", $this->price);
        $stmt->bindParam(":slug", $this->slug);
        $stmt->bindParam(":description", $this->description);


        // execute query
        if($stmt->execute()){
            return true;
        }

        return false;

    }

    // update the product
    function    update(){

        // update query
        $query = "UPDATE
                " . $this->table_name . "
            SET
                name = :name,
                price = :price,
                description = :description,
                slug = :slug
            WHERE
                id = :id";

        // prepare query statement
        $stmt = $this->conn->prepare($query);

        // sanitize
        $this->name=htmlspecialchars(strip_tags($this->name));
        $this->price=htmlspecialchars(strip_tags($this->price));
        $this->description=htmlspecialchars(strip_tags($this->description));
        $this->slug=htmlspecialchars(strip_tags($this->slug));
        $this->id=htmlspecialchars(strip_tags($this->id));

        // bind new values
        $stmt->bindParam(':name', $this->name);
        $stmt->bindParam(':price', $this->price);
        $stmt->bindParam(':slug', $this->slug);
        $stmt->bindParam(':description', $this->description);
        $stmt->bindParam(':id', $this->id);

        // execute the query
        if($stmt->execute()){
            return true;
        }

        return false;
    }
    // delete the product
    function delete(){

        // delete query
        $query = "DELETE FROM " . $this->table_name . " WHERE id = ?";

        // prepare query
        $stmt = $this->conn->prepare($query);

        // sanitize
        $this->id=htmlspecialchars(strip_tags($this->id));

        // bind id of record to delete
        $stmt->bindParam(1, $this->id);

        // execute query
        if($stmt->execute()){
            return true;
        }

        return false;

    }
    // used when filling up the update product form
    function readOne(){

        // query to read single record
        $query = "SELECT * FROM  " . $this->table_name . " WHERE id = ?";

        // prepare query statement
        $stmt = $this->conn->prepare( $query );

        // bind id of product to be updated
        $stmt->bindParam(1, $this->id);

        // execute query
        $stmt->execute();

        // get retrieved row
        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        // set values to object properties
        $this->name = $row['name'];
        $this->price = $row['price'];
        $this->description = $row['description'];
        $this->slug = $row['slug'];
    }
}
?>