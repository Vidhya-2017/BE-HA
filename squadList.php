<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");

require_once 'include/dbconnect.php';
$query = "SELECT ID,SquadName,EventID FROM `squad` WHERE isActive=1 ";
$result = mysqli_query($conn,$query);
$squadset = array();
if(mysqli_num_rows($result) > 0){
    while ($squadrow = mysqli_fetch_assoc($result)){
        $squadset[] = $squadrow;
    } 
    $errcode = 200;
    $status = "Success";
}else{
    $errcode = 500;
    $status = "Failure";
}

echo $result = json_encode(array("errCode"=>$errcode,"status"=>$status,"arrRes" => $squadset));
mysqli_close($conn);

?>