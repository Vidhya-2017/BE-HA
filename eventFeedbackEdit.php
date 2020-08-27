<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: GET, POST, PATCH, PUT, DELETE, OPTIONS");
header("Access-Control-Allow-Headers: Origin, Content-Type, X-Auth-Token");


$json = file_get_contents('php://input');
$data = json_decode($json,true);
require_once 'include/dbconnect.php';

if(isset($data)){
    //$feedbackID =  $data['feedbackID'];
    $event_id = $data['eventID'];
    $client_id = $data['clientID'];
    $rating_cnt = $data['ratingCnt'];
    $txt_comment = $data['txtComment'];
   // $createdBy = $data['createdBy'];
    $updatedBy = $data['updatedBy'];
    //$createdDt = date('Y-m-d h:i:s');
    $updatedDt = date('Y-m-d h:i:s');
}

$query = "UPDATE event_feedback SET  cf_rating='$rating_cnt',cf_comment='$txt_comment', updatedBy='$updatedBy', updatedDate=' $updatedDt' WHERE  cf_eventID='$event_id' and cf_clientID='$client_id'";
$result = mysqli_query($conn,$query);
$isUpdate = mysqli_affected_rows($conn);
if($isUpdate > 0){
    $errcode = 200;
    $status = "Success";
}else{
    $errcode = 404;
    $status = "Failure";
}
echo $result = json_encode(array("errCode"=>$errcode,"status"=>$status));

mysqli_close($conn);
?>