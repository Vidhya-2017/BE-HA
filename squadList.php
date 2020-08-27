<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: GET, POST, PATCH, PUT, DELETE, OPTIONS");
header("Access-Control-Allow-Headers: Origin, Content-Type, X-Auth-Token");

$json = file_get_contents('php://input');
$data = json_decode($json,true);

require_once 'include/dbconnect.php';
if(isset($data)){
    $eventid = $data['eventID'];
    if($eventid!=''){
    $addQry =" and EventID ='".$eventid."'";
    }else{
        $addQry="";
    }
}else{
    $addQry="";
}
 $query = "SELECT ID,SquadName,EventID,squad_team_img FROM `squad` WHERE isActive=1". $addQry;
$result = mysqli_query($conn,$query);
$squadset = array();
if(mysqli_num_rows($result) > 0){
    while ($squadrow = mysqli_fetch_assoc($result)){
        $squadset[] = $squadrow;
    } 
    $errcode = 200;
    $status = "Success";
}else{
    $errcode = 404;
    $status = "No Result";
}

echo $result = json_encode(array("errCode"=>$errcode,"status"=>$status,"arrRes" => $squadset));
mysqli_close($conn);

?>