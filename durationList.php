<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: GET, POST, PATCH, PUT, DELETE, OPTIONS");
header("Access-Control-Allow-Headers: Origin, Content-Type, X-Auth-Token");

require_once 'include/dbconnect.php';
$query = "SELECT DurationID,Duration FROM `duration` WHERE isActive=1 ";
$result = mysqli_query($conn,$query);
$durationset = array();
if(mysqli_num_rows($result) > 0){
    while ($durationrow = mysqli_fetch_assoc($result)){
        $durationset[] = $durationrow;
    } 
    $errcode = 200;
    $status = "Success";
}else{
    $errcode = 404;
    $status = "Failure";
}

echo $result = json_encode(array("errCode"=>$errcode,"status"=>$status,"arrRes" => $durationset));

mysqli_close($conn);
?>