<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
require_once 'include/dbconnect.php';
$addQuery='';

if(isset($_POST["searchEvntName"])){
    $findevntName = $_POST["searchEvntName"];
     $addQuery.="and EventName LIKE '%".$findevntName."%'";

}
if(isset($_POST["searchClntName"])){
    $findclntName = $_POST["searchClntName"];
   $addQuery.="and Client = '".$findclntName."'";
}
if(isset($_POST["searchDuration"])){
    $findduration = $_POST["searchDuration"];
   $addQuery.="and Duration = '".$findduration."'";
}
if(isset($_POST["searchSkillName"])){
    $findskill = $_POST["searchSkillName"];
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
     $errcode = 500;
     $status = "Failure";
 }
 echo $result = json_encode(array("errCode"=>$errcode,"status"=>$status,"arrRes" => $durationset));

mysqli_close($conn);
?>