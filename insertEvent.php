<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");

require_once 'include/dbconnect.php';

$event_name			 		= $_POST['EventName'];
$duration			 		= $_POST['Duration'];
$skills 			 		= $_POST['Skills'];
$client						 = $_POST['Client'];
$assessment_scale	 			= $_POST['AssessmentScale'];
$problem_solving_skills_tested = $_POST['ProblemSolvingSkillTested'];
$technical_skills_tested		 = $_POST['TechnicalSkilslsTested'];
$communication_skills_tested = $_POST['CommunicationSkillIsTested'];
$logical_skills_tested 	= $_POST['LogicalSkillsIsTested'];


$query = "INSERT INTO register_event ( EventName, Date, Duration, Skills, Client, AssessmentScale, ProblemSolvingSkillTested, TechnicalSkilslsTested, CommunicationSkillIsTested, LogicalSkillsIsTested) VALUES ( '$event_name', now(), '$duration', '$skills ', '$client', '$assessment_scale', '$problem_solving_skills_tested', '$technical_skills_tested', '$communication_skills_tested', '$logical_skills_tested'); ";

$result = mysqli_query($conn,$query);

if($result == 1){
    $errcode = 200;
    $status = "Success";
}else{
    $errcode = 500;
    $status = "Failure";
}

echo $result = json_encode(array("errCode"=>$errcode,"status"=>$status));

mysqli_close($conn);
?>