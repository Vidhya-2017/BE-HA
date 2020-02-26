<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");

require_once 'include/dbconnect.php';
$query = "SELECT ClientId,ClientName FROM `client` WHERE isActive=1 ";
$result = mysqli_query($conn,$query);
$clientset = array();
if(mysqli_num_rows($result) > 0){
    while ($clientrow = mysqli_fetch_assoc($result)){
        $clientset[] = $clientrow;
    } 
    $errcode = 200;
    $status = "Success";
}else{
    $errcode = 500;
    $status = "Failure";
}

echo $result = json_encode(array("errCode"=>$errcode,"status"=>$status,"arrRes" => $clientset));

mysqli_close($conn);
?>