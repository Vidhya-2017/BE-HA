<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: GET, POST, PATCH, PUT, DELETE, OPTIONS");
header("Access-Control-Allow-Headers: Origin, Content-Type, X-Auth-Token");

require_once 'include/dbconnect.php';


$json = file_get_contents('php://input');
$data = json_decode($json,true);

$squad_id =$data["squad_id"];

$query = "SELECT c.`EmpName`, c.`EmpID`,sq.CandidateID ,c.candidate_image  FROM `squad_candidates` sq JOIN `candidate_registration` c ON sq.`CandidateID` = c.`ID` WHERE sq.`SquadID`=$squad_id and c.`isActive`=1";
$result = mysqli_query($conn,$query);
$candidatesdata = array();
if(mysqli_num_rows($result) > 0){
    while ($candidates = mysqli_fetch_assoc($result)){
        $candidatesdata[] = $candidates;
    } 
    $errcode = 200;
    $status = "Success";
}else{
    $errcode = 404;
    $status = "Failure";
}

echo $result = json_encode(array("errCode"=>$errcode,"status"=>$status,"arrRes" => $candidatesdata));

mysqli_close($conn);
?>