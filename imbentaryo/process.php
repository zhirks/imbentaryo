<?php
  session_start();

  // check for logged in session
  if(!$_SESSION['loggedIn'])
  {
    header("Location: login.php");
    exit;
  }

  if(isset($_SESSION["user_id"])){
    $mysqli = require __DIR__ . "/dbconnect.php";

    $sql = "SELECT * FROM login
            WHERE id = {$_SESSION["user_id"]}";

    $result = $mysqli->query($sql);
    $user = $result->fetch_assoc();
  }
  
require_once('dbclass.php');
require 'vendor/autoload.php';

$class = new DataImport();

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

if(isset($_POST['save_excel_data']))
{
    $filename = $_FILES['import_file']['name']; //get the fullname of the file
    $file_ext = pathinfo($filename, PATHINFO_EXTENSION);

    $allowed_ext = ['xls','csv','xlsx'];
    
    if(in_array($file_ext, $allowed_ext))
    {
        $inputFileNamePath = $_FILES['import_file']['tmp_name'];
        $spreadsheet = \PhpOffice\PhpSpreadsheet\IOFactory::load($inputFileNamePath);

        $data1 = $spreadsheet->setActiveSheetIndexByName('STORES')->toArray();
        $header1 = 0;
        foreach($data1 as $key => $value){

            if($header1 > 0){
                $class->insert_store($value);
            }else{
                $header1 = 1;
            }
        }

        $data2 = $spreadsheet->setActiveSheetIndexByName('EMPLOYEES')->toArray();
        $header2 = 0;
        foreach($data2 as $key => $value){

            if($header2 > 0){
                $class->insert_emp($value);
            }else{
                $header2 = 1;
            }
        }

        $data3 = $spreadsheet->getSheet(2);
        $worksheetName = $data3->getTitle();

        //$string = "SM_MEGAMALL-A_ROG_ZEPHYRUS";
        $parts = explode("_", $worksheetName);

        if (count($parts) > 2) {
            // Join the parts after the second underscore
            $result = implode("_", array_slice($parts, 2));
        } else {
            // If there are less than 3 parts, there won't be anything after the second underscore
            $result = "";
        }
        
        echo $result;
        
        $header3 = 0;


        /*
        foreach($data as $row)
        {
            $store_name = $row['0'];
            $store_address = $row['1'];
            $store_manager = $row['2'];
            $store_email = $row['3'];
            $store_mobile = $row['4'];

            
        }
        */
    }
    else
    {
        $_SESSION['message'] = "Invalid file type!";
        header('Location: index.php');
        exit(0);
    }
}
?>