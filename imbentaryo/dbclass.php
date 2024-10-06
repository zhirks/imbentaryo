<?php

session_start();
define("DATABASE", "imbentaryodb");


class DataImport {
    private $server = "mysql:host=localhost;dbname=".DATABASE;
    private $user = "root";
    private $password = "";
    private $options = array(PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION, PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC);
    protected $con;

    public function connect(){
        try {
            $this->con = new PDO($this->server, $this->user, $this->password, $this->options);
            return $this->con;
        } catch (PDOException $e) {
            echo "There is some problem in the connection : ".$e->getMessage();
        }
    }

    /*
    public function insert_data($array)
    {
        $first_name = trim($array[0]);
        $last_name = trim($array[1]);
        $email = trim($array[2]);

        try {
            $connection = $this->connect();
            $stmt = $connection->prepare("INSERT INTO users (`first_name`,`last_name`,`email`) VALUE (?,?,?)");
            $stmt->execute([$first_name, $last_name, $email]);
            //$id = $connection->lastInsertId();
            return "success";
        } catch (PDOException $e) {
            echo "There is some problem in inserting data : ".$e->getMessage();
        }
    }
    */

    public function insert_stocks($array){
        $stocks_variant = trim($array[0]);
        $stocks_serial = trim($array[1]);
        $stocks_status = trim($array[2]);
        $stocks_prodid = trim($array[3]);
        $stocks_empid = trim($array[4]);
        $stocks_storeid = trim($array[5]);

        try {
            $connection = $this->connect();
            
            $stmt = $connection->prepare(
                "INSERT INTO stocks (stocks_variant, stocks_serial, stocks_status, prod_id, emp_id, store_id) 
                SELECT * FROM (SELECT :stocks_variant, :stocks_serial, :stocks_status) AS tmp 
                WHERE NOT EXISTS (SELECT stocks_serial FROM stocks WHERE stocks_serial = :stocks_serial LIMIT 1);");
           
            $stmt->bindParam(':stocks_variant', $stocks_variant, PDO::PARAM_STR);
            $stmt->bindParam(':stocks_serial', $stocks_serial, PDO::PARAM_STR);
            $stmt->bindParam(':stocks_status', $stocks_status, PDO::PARAM_STR);
            $stmt->bindParam(':email', $emp_email, PDO::PARAM_STR);
            $stmt->bindParam(':mobile', $emp_mobile, PDO::PARAM_INT);

            
            $stmt->execute();
            $_SESSION['message'] = "Updated employee details!";
            header('Location: index.php');
        } catch (PDOException $e) {
            echo "There is some problem in inserting data : ".$e->getMessage();
        }
    }

    public function insert_product($array){
        $prod_name = trim($array[0]);
        $prod_variant = trim($array[1]);
        $prod_specs = trim($array[2]);

        try {
            $connection = $this->connect();
            
            $stmt = $connection->prepare(
                "INSERT INTO product (prod_name, prod_variant, prod_specs) 
                SELECT * FROM (SELECT :prod_name, :prod_variant, :prod_specs) AS tmp 
                WHERE NOT EXISTS (SELECT prod_variant FROM product WHERE prod_variant = :prod_variant LIMIT 1);");
           
            $stmt->bindParam(':prod_name', $prod_name, PDO::PARAM_STR);
            $stmt->bindParam(':prod_variant', $prod_variant, PDO::PARAM_STR);
            $stmt->bindParam(':prod_specs', $prod_specs, PDO::PARAM_STR);

            
            $stmt->execute();
            $_SESSION['message'] = "Updated employee details!";
            header('Location: index.php');
        } catch (PDOException $e) {
            echo "There is some problem in inserting data : ".$e->getMessage();
        }
    }

    public function insert_store($array){
        $store_name = trim($array[0]);
        $store_address = trim($array[1]);
        $store_mgr = trim($array[2]);
        $store_email = trim($array[3]);

        try {
            $connection = $this->connect();

            /* WORKING c/o Jeff
            //$stmt = $connection->prepare("INSERT INTO store (`store_name`,`store_address`,`store_mgr`,`store_email`,`store_mobile`) SELECT * FROM (SELECT ?,?,?,?,?) AS tmp WHERE NOT EXISTS ( SELECT `store_name` FROM store WHERE `store_name` = ? ) LIMIT 1;");
            
            
            $stmt = $connection->prepare(
                "INSERT INTO store (`store_name`,`store_address`,`store_mgr`,`store_email`,`store_mobile`) SELECT * FROM (SELECT ?,?,?,?,?) AS tmp WHERE NOT EXISTS ( SELECT store_name FROM store WHERE store_name = ? LIMIT 1);");
            */
            
            $stmt = $connection->prepare(
                "INSERT INTO store (store_name, store_address, store_mgr, store_email) 
                SELECT * FROM (SELECT :storeName,:storeAddress,:storeMgr,:storeEmail) AS tmp 
                WHERE NOT EXISTS (SELECT store_name FROM store WHERE store_name = :storeName LIMIT 1);");
           
            $stmt->bindParam(':storeName', $store_name, PDO::PARAM_STR);
            $stmt->bindParam(':storeAddress', $store_address, PDO::PARAM_STR);
            $stmt->bindParam(':storeMgr', $store_mgr, PDO::PARAM_STR);
            $stmt->bindParam(':storeEmail', $store_email, PDO::PARAM_STR);
            
            $stmt->execute();

            $_SESSION['message'] = "Updated store details!";
            header('Location: index.php');
        } catch (PDOException $e) {
            echo "There is some problem in inserting data : ".$e->getMessage();
        }
    }

    public function insert_emp($array){
        $emp_id = trim($array[0]);
        $emp_fname = trim($array[1]);
        $emp_lname = trim($array[2]);
        $emp_email = trim($array[3]);
        $emp_mobile = trim($array[4]);

        try {
            $connection = $this->connect();
            
            $stmt = $connection->prepare(
                "INSERT INTO employee (emp_fullid, emp_firstname, emp_lastname, emp_email, emp_mobile) 
                SELECT * FROM (SELECT :fullid, :fname, :lname, :email, :mobile) AS tmp 
                WHERE NOT EXISTS (SELECT emp_fullid FROM employee WHERE emp_fullid = :fullid LIMIT 1);");
           
            $stmt->bindParam(':fullid', $emp_id, PDO::PARAM_STR);
            $stmt->bindParam(':fname', $emp_fname, PDO::PARAM_STR);
            $stmt->bindParam(':lname', $emp_lname, PDO::PARAM_STR);
            $stmt->bindParam(':email', $emp_email, PDO::PARAM_STR);
            $stmt->bindParam(':mobile', $emp_mobile, PDO::PARAM_INT);

            
            $stmt->execute();
            $_SESSION['message'] = "Updated employee details!";
            header('Location: index.php');
        } catch (PDOException $e) {
            echo "There is some problem in inserting data : ".$e->getMessage();
        }
    }
}