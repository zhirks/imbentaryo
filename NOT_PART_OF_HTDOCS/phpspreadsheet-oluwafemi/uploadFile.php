<?php
    date_default_timezone_set('Africa/Lagos');
    use PhpOffice\PhpSpreadsheet\Reader\Xlsx;

    class gen_function{
        protected $stmt = null;
        protected $pdo = null;
        public $error ="";
        public $timeDate ="";

    function __construct(){   
        try{ 
            if(!defined("SEVER_HOST"))define('SEVER_HOST','127.0.0.1');
            if(!defined("CHARSET"))define('CHARSET','utf8');
            if(!defined("DATA_BASE_NAME"))define('DATA_BASE_NAME','contact_list');
            if(!defined("USER_NAME"))define('USER_NAME','root');
            if(!defined("PASSWORD"))define('PASSWORD','');    
                
            $this->timeDate = date("Y/m/d H:i:s");          
            $str = "mysql:host=".SEVER_HOST.";charset=".CHARSET;
            
            if(defined('DATA_BASE_NAME')){
                $str .=";dbname=".DATA_BASE_NAME;
            } 
                
            $this->pdo = new PDO ($str,
                                    USER_NAME,PASSWORD,
                                    [
                                        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                                        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                                        PDO::ATTR_EMULATE_PREPARES => 1
                                    ]
                                ); 
            return true;
        }
        catch(Exception $ex){
            print_r($ex);
            die();
        }
    }
        
    function data(){
        try{
            if(isset($_POST['upload'])){
                require_once('vendor/autoload.php');
                $fname = $_FILES["dataFile"]["name"]; 
                $ftype = $_FILES["dataFile"]["type"]; 
                $tmp_fname = $_FILES["dataFile"]["tmp_name"]; 

                // Allowed mime types 
                $excelMimes = array('text/xls', 'text/xlsx', 'application/excel', 'application/vnd.msexcel', 'application/vnd.ms-excel', 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet'); 

                // Validate whether selected file is a Excel file 
                if(!empty($fname) && in_array($ftype, $excelMimes)){    
                    // If the file is uploaded 
                    if(is_uploaded_file($tmp_fname)){ 
                        $reader = new Xlsx(); 
                        $spreadsheet = $reader->load($tmp_fname); 
                        $worksheet = $spreadsheet->getActiveSheet();  
                        $worksheet_arr = $worksheet->toArray(); 

                        // Remove header row 
                        unset($worksheet_arr[0]); 
                        foreach($worksheet_arr as $row){ 
                            $ftNme = $row[0]; 
                            $last = $row[1]; 
                            $email = $row[2]; 

                            // Check whether emic data exists in the database with the same id
                            $prevQuery = "SELECT * FROM `users` WHERE (`email` = '".$email."')"; 
                            $prevResult = $this->pdo->query($prevQuery); 

                            if($prevResult->rowCount() > 0){ 
                                // Update member data in the database 
                                $this->pdo->query("UPDATE `users` SET `firstName`='$ftNme',`lastName`='$last',`email`='$email' WHERE (`email` = '".$email."')"); 
                            }
                            else{                    
                                // Insert member data in the database 
                                $this->pdo->query(
                                            "INSERT INTO `users` (`date`,`firstName`,`lastName`,`email`) VALUES ('$this->timeDate','$ftNme','$last','$email')"
                                            );
                            }     
                        } 
                        $qstring = 'Contact list data synchronized or modified successfully .'; 
                    }
                    else{ 
                        $qstring = 'Oops ! there is a glitch while trying to upload this file, kindly check.Thank you.'; 
                    }
                }
                else{ 
                    $qstring = 'Oops ! the selected file is empty or not really an excel file, kindly check.Thank you.'; 
                }
                print($qstring);      
            }
                
            if (isset($_POST["del_list"])) {
                $card_id = @$_POST["listId"];
                
                if (empty($card_id)) {
                    print "Sorry ! you have to select one or more item to complete this task.";
                } 
                else {
                    foreach ($card_id as $arr) {
                        $id = intval($arr);
                        $stmt = $this->pdo->query(
                                            "DELETE FROM `users` WHERE (`id`=$id)"
                                            );
                    }
                    if ($stmt->rowCount() > 0) {
                        echo "Selected items has been deleted successfully";
                    }
                    $stmt = null;
                }
            }  
            return true;
        }
        catch(Exception $e){
            print_r($e);
            die();
        }   
    }    
    
    //let distroy the connection.......
    function __destruct(){
        $this->pdo=NULL;
    }
}

$obj = new gen_function();
$obj->data();