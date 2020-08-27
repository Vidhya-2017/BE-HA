<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: GET, POST, PATCH, PUT, DELETE, OPTIONS");
header("Access-Control-Allow-Headers: Origin, Content-Type, X-Auth-Token");


$json = file_get_contents('php://input');
$data = json_decode($json,true);
require_once 'include/dbconnect.php';

if(isset($data)){
    $locName = $data['LocName'];
    $locLat = $data['LocLatitude'];
    $locLong = $data['LocLongitude'];
    $Created_Date= date('Y-m-d h:i:s');
    $Created_By	 = $data['CreatedBy'];
 }

$query = "INSERT INTO `event_location` (loc_name,loc_latitude,loc_longitude,isActive,createdBy,createdDate) VALUES ('$locName','$locLat','$locLong','1','$Created_By','$Created_Date')";

$result = mysqli_query($conn,$query);
if(mysqli_insert_id($conn)>0){
    $errcode = 200;
    $status = "Success";
    $locId = mysqli_insert_id($conn);
}else{
    $errcode = 404;
    $status = "Failure";
    $locId = "";
}

echo $result = json_encode(array("errCode"=>$errcode,"status"=>$status,"arrRes" => $locId));

mysqli_close($conn);
?>