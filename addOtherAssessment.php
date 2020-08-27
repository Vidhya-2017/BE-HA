<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: GET, POST, PATCH, PUT, DELETE, OPTIONS");
header("Access-Control-Allow-Headers: Origin, Content-Type, X-Auth-Token");


$json = file_get_contents('php://input');
$data = json_decode($json,true);
require_once 'include/dbconnect.php';

if(isset($data)){
    $assName = $data['AssessName'];
    $assValue = $data['AssessValue'];
    $createdBy = $data['createdBy'];
    $createdDt =  date('Y-m-d h:i:s');
}

//updatedBy,updatedDate
  $query = "INSERT INTO `other_assessmentscale` (OtherAssementScaleName,ScaleValue,isActive,createdBy,createdDate) VALUES ('$assName','$assValue','1','$createdBy','$createdDt')";

$result = mysqli_query($conn,$query);
if(mysqli_insert_id($conn)>0){
    $errcode = 200;
    $status = "Success";
    $assId = mysqli_insert_id($conn);
}else{
    $errcode = 404;
    $status = "Failure";
    $assId = "";
}

echo $result = json_encode(array("errCode"=>$errcode,"status"=>$status,"arrRes" => $assId));

mysqli_close($conn);
?>