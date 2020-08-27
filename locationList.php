<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: GET, POST, PATCH, PUT, DELETE, OPTIONS");
header("Access-Control-Allow-Headers: Origin, Content-Type, X-Auth-Token");

require_once 'include/dbconnect.php';

$query = "SELECT loc_id ,loc_name, loc_latitude, loc_longitude FROM `event_location` WHERE isActive=1 ";
$result = mysqli_query($conn,$query);
$locset = array();
if(mysqli_num_rows($result) > 0){
    while ($locrow = mysqli_fetch_assoc($result)){
        $locset[] = $locrow;
    } 
    $errcode = 200;
    $status = "Success";
}else{
    $errcode = 404;
    $status = "Failure";
    $locset=array();
}

echo $result = json_encode(array("errCode"=>$errcode,"status"=>$status,"arrRes" => $locset));

mysqli_close($conn);
?>