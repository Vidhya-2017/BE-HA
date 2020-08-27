<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: GET, POST, PATCH, PUT, DELETE, OPTIONS");
header("Access-Control-Allow-Headers: Origin, Content-Type, X-Auth-Token");

$json = file_get_contents('php://input');
$data = json_decode($json,true);

require_once 'include/dbconnect.php';
if(isset($data)){
    $userName = $data['userName'];
    $password = $data['password'];
    $userset = array();
    
    $logsel =  mysqli_query($conn,"SELECT user_id,first_name,last_name,isAdmin FROM `user_login` where email='$userName' and password='$password' and isActive=1");
    
    if(mysqli_num_rows($logsel) == 1){
        $errcode = 200;
        $status = "Success";
        $userset[] = mysqli_fetch_assoc($logsel);
    }else{
         $errcode = 404;
        $status = "Incorrect credential";
    }
}

echo $result = json_encode(array("errCode"=>$errcode,"status"=>$status,"UserSet"=>$userset));
mysqli_close($conn);	
?>