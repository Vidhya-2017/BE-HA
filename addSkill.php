<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: GET, POST, PATCH, PUT, DELETE, OPTIONS");
header("Access-Control-Allow-Headers: Origin, Content-Type, X-Auth-Token");


$json = file_get_contents('php://input');
$data = json_decode($json,true);
require_once 'include/dbconnect.php';

if(isset($data)){
    $skillName = $data['skillName'];
}

$query = "INSERT INTO `skills` (Skills,isActive) VALUES ('$skillName','1')";

$result = mysqli_query($conn,$query);
if(mysqli_insert_id($conn)>0){
    $errcode = 200;
    $status = "Success";
    $skillId = mysqli_insert_id($conn);
}else{
    $errcode = 404;
    $status = "Failure";
    $skillId = "";
}

echo $result = json_encode(array("errCode"=>$errcode,"status"=>$status,"arrRes" => $skillId));

mysqli_close($conn);
?>