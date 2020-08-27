<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: GET, POST, PATCH, PUT, DELETE, OPTIONS");
header("Access-Control-Allow-Headers: Origin, Content-Type, X-Auth-Token");

$json = file_get_contents('php://input');
require_once 'include/dbconnect.php';
$query = "SELECT user_id,first_name,last_name ,contact_no ,email ,sapID  FROM `user_login` WHERE isActive='1'";
$result = mysqli_query($conn,$query);
$skillset = array();
if(mysqli_num_rows($result) > 0){
    while ($skillrow = mysqli_fetch_assoc($result)){
        $skillset[] = $skillrow;
    } 
    $errcode = 200;
    $status = "Success";
}else{
    $errcode = 404;
    $status = "Failure";
}

echo $result = json_encode(array("errCode"=>$errcode,"status"=>$status,"userList" => $skillset));

mysqli_close($conn);
?>