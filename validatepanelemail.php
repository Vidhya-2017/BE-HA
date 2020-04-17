<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");

$json = file_get_contents('php://input');
$data = json_decode($json,true);
require_once 'include/dbconnect.php';

if(isset($data)){

    $emailId  = $data['EmailID'];
    $Query = "SELECT `pl_email` FROM `event_panel_list` WHERE `pl_email` ='". $emailId."'";
    $result = mysqli_query($conn,$Query);
    if(mysqli_num_rows($result) == 0){
        $errcode = 200;
        $status = "Success";
    }else{
        $errcode = 500;
        $status = "Already Exist";
    }
    
}else{
    $errcode = 500;
    $status = "Oops went wrong!!!";
}
echo $result = json_encode(array("errCode"=>$errcode,"status"=>$status));
mysqli_close($conn);

?>