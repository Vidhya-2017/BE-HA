<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: GET, POST, PATCH, PUT, DELETE, OPTIONS");
header("Access-Control-Allow-Headers: Origin, Content-Type, X-Auth-Token");


$json = file_get_contents('php://input');
$data = json_decode($json,true);
require_once 'include/dbconnect.php';

if(isset($data)){
    $message = mysqli_real_escape_string($data['message']);
    $remote_addr = mysqli_real_escape_string($data['client_ip']);
    $request_uri = mysqli_real_escape_string($data['request_uri']);
    $error_code = mysqli_real_escape_string($data['error_code']);

}


date_default_timezone_set("Asia/Kolkata");

// Escape values
/* $message     = $conn->real_escape_string($message);
$remote_addr = $conn->real_escape_string($remote_addr);
$request_uri = $conn->real_escape_string($request_uri); 
$error_code = $conn->real_escape_string($error_code); */
$logDate = date("Y-m-d H:i:s");

// Construct query
$sql = "INSERT INTO error_log (client_ip, request_uri, message, error_code,log_date) VALUES('$remote_addr', '$request_uri','$message','$error_code','$logDate')";
$result = mysqli_query($conn,$sql);
if($result) {
    $errcode = 200;
    $status = "Success";
}
else {
    $errcode = 404;
    $status = "Failure";
}   
}

echo $result = json_encode(array("errCode"=>$errcode,"status"=>$status,"arrRes" => $skillId));

mysqli_close($conn);
?>