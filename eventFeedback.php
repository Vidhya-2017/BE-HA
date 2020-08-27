<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: GET, POST, PATCH, PUT, DELETE, OPTIONS");
header("Access-Control-Allow-Headers: Origin, Content-Type, X-Auth-Token");


$json = file_get_contents('php://input');
$data = json_decode($json,true);
require_once 'include/dbconnect.php';

if(isset($data)){
    $event_id = $data['eventID'];
    $client_id = $data['clientID'];
    $rating_cnt = $data['ratingCnt'];
    $txt_comment = $data['txtComment'];
    $createdBy = $data['createdBy'];
   // $updatedBy = $data['updatedBy'];
    $createdDt = date('Y-m-d h:i:s');
    //$updatedDt = date('Y-m-d h:i:s');
}

 $query = "INSERT INTO `event_feedback` (cf_eventID,cf_clientID,cf_rating,cf_comment,isActive,createdBy,createdDate) VALUES ('$event_id','$client_id','$rating_cnt','$txt_comment','1','$createdBy','$createdDt')";

$result = mysqli_query($conn,$query);
if(mysqli_insert_id($conn)>0){
    $errcode = 200;
    $status = "Success";
   // $locId = mysqli_insert_id($conn);
}else{
    $errcode = 404;
    $status = "Failure";
   // $locId = "";
}

echo $result = json_encode(array("errCode"=>$errcode,"status"=>$status));

mysqli_close($conn);
?>