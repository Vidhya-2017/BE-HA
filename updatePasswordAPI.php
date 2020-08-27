<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: GET, POST, PATCH, PUT, DELETE, OPTIONS");
header("Access-Control-Allow-Headers: Origin, Content-Type, X-Auth-Token");


$json = file_get_contents('php://input');
$data = json_decode($json,true);
require_once 'include/dbconnect.php';
try {
    if(isset($data)){
    
        $emailId = $data['EmailID'];
        $password = $data['Password'];
       
        $sql = "UPDATE `user_login` SET `password`='$password' WHERE `email`= '".$emailId."'";
        $result = mysqli_query($conn,$sql);
        if($result) {
            $errcode = 200;
            $status = "Success";
        }
        else {
            $errcode = 404;
            $status = "Failure";
            throw new Exception('Some error occured in updating data'); 
        }   
    }else{
        $errcode = 404;
        $status = "Oops something went wrong!!!";
        throw new Exception('Some error occured in sending data'); 
    }
}
catch (exception $e) {
    mysql_log( $e->getMessage(),mysqli_errno($conn),$conn);
}

echo $result = json_encode(array("errCode"=>$errcode,"status"=>$status));

mysqli_close($conn);
?>