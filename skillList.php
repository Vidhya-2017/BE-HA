<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");

require_once 'include/dbconnect.php';
$query = "SELECT SkillId,Skills FROM `skills` WHERE isActive=1 ";
$result = mysqli_query($conn,$query);
$skillset = array();
if(mysqli_num_rows($result) > 0){
    while ($skillrow = mysqli_fetch_assoc($result)){
        $skillset[] = $skillrow;
    } 
    $errcode = 200;
    $status = "Success";
}else{
    $errcode = 500;
    $status = "Failure";
}

echo $result = json_encode(array("errCode"=>$errcode,"status"=>$status,"arrRes" => $skillset));

mysqli_close($conn);
?>