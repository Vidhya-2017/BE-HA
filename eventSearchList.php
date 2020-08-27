<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: GET, POST, PATCH, PUT, DELETE, OPTIONS");
header("Access-Control-Allow-Headers: Origin, Content-Type, X-Auth-Token");

$json = file_get_contents('php://input');
$data = json_decode($json,true);

require_once 'include/dbconnect.php';
$addQuery='';

if(isset($data["searchEvntName"])){
    $findevntName = $data["searchEvntName"];
     $addQuery.="and EventName LIKE '%".$findevntName."%'";

}
if(isset($data["searchClntName"])){
    $findclntName = $data["searchClntName"];
   $addQuery.="and Client = '".$findclntName."'";
}
if(isset($data["searchDuration"])){
    $findduration = $data["searchDuration"];
   $addQuery.="and Duration = '".$findduration."'";
}
if(isset($data["searchSkillName"])){
    $findskill = $data["searchSkillName"];
    $addQuery.="and Skills = '".$findskill."'";
}

 $query = "SELECT * FROM `register_event` WHERE EventID > 0 ".$addQuery;
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