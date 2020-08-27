<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: GET, POST, PATCH, PUT, DELETE, OPTIONS");
header("Access-Control-Allow-Headers: Origin, Content-Type, X-Auth-Token");


$json = file_get_contents('php://input');
$data = json_decode($json,true);
require_once 'include/dbconnect.php';

if(isset($data)){
    $competancy_rating = $data['CompentancyRating'];
    $squad_id = $data['SquadID'];
    $event_id = $data['EventID'];
    $candidate_id = $data['CandidateID'];
    $updatedDt = date('Y-m-d h:i:s');
    $updatedBy = $data['updatedBy'];
 }

 $query = "UPDATE  `squad_feedback` SET sq_final_status='$competancy_rating', updatedBy='$updatedBy',updatedDate='$updatedDt' WHERE squad_id='$squad_id' and candidate_id='$candidate_id' and eventId='$event_id' and createdBy='$updatedBy' and sprintLevel='Show and Tell assesment' ";

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