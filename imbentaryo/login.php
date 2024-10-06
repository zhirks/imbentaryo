<?php
    $mysqli = require __DIR__ . "/dbconnect.php";
    $is_invalid = false;

    if(isset($_POST['login_form'])){
        $username = mysqli_real_escape_string($mysqli, $_POST['username']);
        $password = mysqli_real_escape_string($mysqli, $_POST['password']);
        
        if($username == NULL || $password == NULL){
            $res = [
                'status' => 422,
                'message' => 'All fields are mandatory'
            ];
            echo json_encode($res);
            return false;
        }
        
        $query = sprintf("SELECT * FROM login
                        WHERE username = '%s'",
                        $username);
        
        $result = $mysqli->query($query);
        $user = $result->fetch_assoc();
        
        
        if($user){  //check if the entered credential has any match, if yes it will return true
            if(password_verify($password, $user["password_hash"])){
                
                session_start();

                session_regenerate_id();

                $_SESSION['user_id'] = $user['id'];
                $_SESSION['loggedIn'] = true;
                
                //header("Location: dashboard.php");
                //exit;

                $res = [
                    'status' => 200,
                    'message' => 'Login successful!'
                ];
                echo json_encode($res);
                return false;

            }
            else{
                $res = [
                    'status' => 401,
                    'message' => 'Login failed!'
                ];
                echo json_encode($res);
                return false;
            }
        }
        else{
            $res = [
                'status' => 422,
                'message' => 'Login failed!'
            ];
            echo json_encode($res);
            return false;
        }
        

        $is_invalid = true;
    }  
?>