<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: GET, POST, PATCH, PUT, DELETE, OPTIONS");
header("Access-Control-Allow-Headers: Origin, Content-Type, X-Auth-Token");

$json = file_get_contents('php://input');
$data = json_decode($json,true);

require_once 'include/dbconnect.php';
$addQuery='';

// EMP Name 
if(isset($data["searchEmpName"])){
     $findempName = $data["searchEmpName"];
     $addQuery.="and a.EmpName LIKE '%".$findempName."%'";
}

//Skill Name
if(isset($data["searchSkill"])){
    $findSkill = $data["searchSkill"];
   $addQuery.="and a.Skills = '".$findSkill."'";
}

//Selected Emp
if(isset($data["searchSelectedEmp"])){
    $findSelEmp = $data["searchSelectedemp"];
   $addQuery.="and a.isSelected = '".$findSelEmp."'";
}

// Search Active Emp 
if(isset($data["searchActiveEmp"])){
    $findActiveEmp = $data["searchActiveEmp"];
    $addQuery.="and a.isActive = '".$findActiveEmp."'";
}

// Search Two Dates
if(isset($data["FromDate"]) && isset($data["ToDate"])){
    $from_date = $data["FromDate"];
    $to_date = $data["ToDate"];
    $addQuery.="and a.StartDate BETWEEN '".$from_date."' AND '".$to_date."'";
}

//  Search Experience
if(isset($data["searchEmpExp"])){
    $findEmpExp = $data["searchEmpExp"];
    $addQuery.="and a.Expereince = '".$findEmpExp."'";
}

// Search Relevant Experience.
if(isset($data["searchRelExp"])){
    $findRelEmp = $data["searchRelExp"];
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
     $errcode = 404;
     $status = "Failure";
 }
 echo $result = json_encode(array("errCode"=>$errcode,"status"=>$status,"arrRes" => $durationset));
 mysqli_close($conn);
?>