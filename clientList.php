<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: GET, POST, PATCH, PUT, DELETE, OPTIONS");
header("Access-Control-Allow-Headers: Origin, Content-Type, X-Auth-Token");

$json = file_get_contents('php://input');
$data = json_decode($json,true);
require_once 'include/dbconnect.php';
if(isset($data["eventId"])){
    $evntId = $data["eventId"];
     if( $data["eventId"]!='' ){
    $query = "SELECT a.ClientId,a.ClientName FROM `client` a INNER JOIN register_event b ON a.ClientId = b.Client WHERE isActive=1 and EventID ='$evntId'";
     }else{
         $query = "SELECT ClientId,ClientName FROM `client` WHERE isActive=1 ";
     }
}else{
   
    $query = "SELECT ClientId,ClientName FROM `client` WHERE isActive=1 ";
}


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