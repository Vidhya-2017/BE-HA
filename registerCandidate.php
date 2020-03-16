<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");

$json = file_get_contents('php://input');
$data = json_decode($json,true);

require_once 'include/dbconnect.php';

	$emp_id	= $data['EmpID'];
	$event_id	= $data['EventID'];
	$emp_name = $data['EmpName'];
	$skills = implode(",",$data['Skills']);
	$start_date	= $data['StartDate'];
	$contact_no	= $data['ContactNo'];
	$expereince = $data['Expereince'];
	$relevant_experience = $data['RelevantExperience'];
	$created_date = $data['CreatedDate'];
	$created_by	= $data['CreatedBy'];

	$select_query = "SELECT e.`ID`  FROM `candidate_event` e INNER JOIN `candidate_registration` c ON e.`CanidateID`= c.`ID` WHERE c.EmpID =$emp_id and e.`isActive`=1";
	$select_result = mysqli_query($conn,$select_query);

	if(mysqli_num_rows($select_result) == 0){
   
		$query = "INSERT INTO candidate_registration (EmpID, EmpName, Skills, StartDate, ContactNo, Expereince, RelevantExperience, CreatedDate, CreatedBy) VALUES ($emp_id, '$emp_name', '$skills', '$start_date', '$contact_no', '$expereince', '$relevant_experience', '$created_date', '$created_by')";
		
		$result = mysqli_query($conn,$query);
		$candidateId = mysqli_insert_id($conn);
	
		if($result == 1){
			$query = "INSERT INTO `candidate_event` (`CanidateID`, `EventID`, `CreatedDate`, `CreatedBy`) VALUES ($candidateId, $event_id, '$created_date', '$created_by')";
			
			$result = mysqli_query($conn,$query);
		}else{
			$errcode = 500;
			$status = "Failure";
		}
		
		$errcode = 200;
		$status = "Success";
	}else{
		$errcode = 500;
		$status = "Candidate already allocated to another event";
	}


	echo $result = json_encode(array("errCode"=>$errcode,"status"=>$status));

mysqli_close($conn);
?>