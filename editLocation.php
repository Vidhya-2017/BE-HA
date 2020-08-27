<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: GET, POST, PATCH, PUT, DELETE, OPTIONS");
header("Access-Control-Allow-Headers: Origin, Content-Type, X-Auth-Token");


$json = file_get_contents('php://input');
$data = json_decode($json,true);
require_once 'include/dbconnect.php';

if(isset($data)){
    $locid = $data['LocID'];
    $locName = $data['LocName'];
    $locLat = $data['LocLatitude'];
    $locLong = $data['LocLongitude'];
    $updatedDt = $data['UpdatedDate'];
    $updatedBy = $data['updatedBy'];
    $isactive = ($data['isactive']== true ? 1 : 0);
 }

$query = "UPDATE  `event_location` SET loc_name='$locName' ,loc_latitude='$locLat',loc_longitude='$locLong',updatedBy='$updatedBy',updatedDate='$updatedDt',isActive='$isactive' WHERE loc_id='$locid'";

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