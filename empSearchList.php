<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
require_once 'include/dbconnect.php';
$addQuery='';

// EMP Name 
if(isset($_POST["searchEmpName"])){
     $findempName = $_POST["searchEmpName"];
     $addQuery.="and a.EmpName LIKE '%".$findempName."%'";
}

//Skill Name
if(isset($_POST["searchSkill"])){
    $findSkill = $_POST["searchSkill"];
   $addQuery.="and a.Skills = '".$findSkill."'";
}

//Selected Emp
if(isset($_POST["searchSelectedEmp"])){
    $findSelEmp = $_POST["searchSelectedemp"];
   $addQuery.="and a.isSelected = '".$findSelEmp."'";
}

// Search Active Emp 
if(isset($_POST["searchActiveEmp"])){
    $findActiveEmp = $_POST["searchActiveEmp"];
    $addQuery.="and a.isActive = '".$findActiveEmp."'";
}

// Search Two Dates
if(isset($_POST["FromDate"]) && isset($_POST["ToDate"])){
    $from_date = $_POST["FromDate"];
    $to_date = $_POST["ToDate"];
    $addQuery.="and a.StartDate BETWEEN '".$from_date."' AND '".$to_date."'";
}

//  Search Experience
if(isset($_POST["searchEmpExp"])){
    $findEmpExp = $_POST["searchEmpExp"];
    $addQuery.="and a.Expereince = '".$findEmpExp."'";
}

// Search Relevant Experience.
if(isset($_POST["searchRelExp"])){
    $findRelEmp = $_POST["searchRelExp"];
    $addQuery.="and a.RelevantExperience = '".$findRelEmp."'";
}

$query = "SELECT * FROM `candidate_registration` a INNER JOIN `skills` b on a.Skills = b.SkillId WHERE a.ID > 0 ".$addQuery;
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