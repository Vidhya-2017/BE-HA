<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");

$json = file_get_contents('php://input');
$data = json_decode($json,true);


require_once 'include/dbconnect.php';

$event_name			 		= $data['EventName'];
$event_location				= $data['Location'];
$duration			 		= $data['Duration'];
$skills 			 		= $data['Skills'];
$client						 = $data['Client'];
$assessment_scale	 			= $data['AssessmentScale'];
$problem_solving_skills_tested = $data['ProblemSolvingSkillTested'];
$technical_skills_tested		 = $data['TechnicalSkilslsTested'];
$communication_skills_tested = $data['CommunicationSkillIsTested'];
$logical_skills_tested 	= $data['LogicalSkillsIsTested'];


$query = "INSERT INTO register_event ( EventName, Location, Date, Duration, Skills, Client, AssessmentScale, ProblemSolvingSkillTested, TechnicalSkilslsTested, CommunicationSkillIsTested, LogicalSkillsIsTested) VALUES ( '$event_name', '$event_location', now(), '$duration', '$skills ', '$client', '$assessment_scale', '$problem_solving_skills_tested', '$technical_skills_tested', '$communication_skills_tested', '$logical_skills_tested'); ";

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