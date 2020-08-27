<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: GET, POST, PATCH, PUT, DELETE, OPTIONS");
header("Access-Control-Allow-Headers: Origin, Content-Type, X-Auth-Token");

$json = file_get_contents('php://input');
$data = json_decode($json,true);
require_once 'include/dbconnect.php';

if(isset($data)){

    $clientName  = $data['ClientName'];
    $clientStatus = $data['ClientStatus'];

    $Query = "INSERT INTO client ( ClientId, ClientName, isActive) VALUES ('','$clientName','$clientStatus')";
    $result = mysqli_query($conn,$Query);
    if(mysqli_insert_id($conn)>0){
        $errcode = 200;
        $status = "Success";
    }else{
        $errcode = 500;
        $status = "Failure";
    }

}else{
    $errcode = 404;
    $status = "Failure";
}
echo $result = json_encode(array("errCode"=>$errcode,"status"=>$status));
mysqli_close($conn);

?>