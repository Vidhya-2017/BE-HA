<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header('Access-Control-Allow-Methods: GET, POST, PATCH, PUT, DELETE, OPTIONS');
header('Access-Control-Allow-Headers: Origin, Content-Type, X-Auth-Token');

$json = file_get_contents('php://input');
$data = json_decode($json,true);

require_once 'include/dbconnect.php';

	$emp_id	= $data['EmpID'];
	$event_id	= $data['EventID'];
	$emp_name = $data['EmpName'];
	$gender = $data['Gender'];
	$skills = implode(",",$data['Skills']);

	$addSkill = $data['AddSkills'];
	//$addSkill = isset($data['AddSkills']) ? $data['AddSkills'] : '';
	$email_id =$data['EmailId'];
	//$email_id = isset($data['EmailId']) ? $data['EmailId'] : '';
	$isExternal =  ($data['isExternal'] == true ? 1 : 0);
	//$candidateImage = $data['candidateImage'];
	$candidateImage = isset($data['candidateImage']) ? $data['candidateImage'] : '';

	$start_date	= $data['StartDate'];
	$contact_no	= $data['ContactNo'];
	$expereince = $data['Expereince'];
	$relevant_experience = $data['RelevantExperience'];
	$dna_passscore = $data['DNAPassScore'];
	$created_date = $data['CreatedDate'];
	$created_by	= $data['CreatedBy']; 
	$updated_date = $data['UpdatedDate'];
	$updated_by	= $data['UpdatedBy']; 
	
	
	if($data['EmpID'] != '0' && $isExternal == 0)
	{
		
		// $select_query = "SELECT e.`ID`  FROM `candidate_event` e INNER JOIN `candidate_registration` c ON e.`CanidateID`= c.`ID` WHERE c.EmpID ='$emp_id' and e.`isActive`=1";
		
		
		$select_query = "SELECT e.`ID`  FROM `candidate_event` e INNER JOIN `candidate_registration` c ON e.`CanidateID`= c.`ID` WHERE c.ContactNo ='$contact_no' and e.EventID = '$event_id' and e.`isActive`=1";
		
		$select_result = mysqli_query($conn,$select_query);
		
		
		$select_query1 = "SELECT c.`ID` as CanidateID  FROM  `candidate_registration` c WHERE c.ContactNo ='$contact_no' and c.`isActive`=1";
		
		$select_result1 = mysqli_query($conn,$select_query1);

		if(mysqli_num_rows($select_result1) != 0 && mysqli_num_rows($select_result) == 0) {
			$skillrow = mysqli_fetch_assoc($select_result1);
			$candidateId = $skillrow['CanidateID'];
			
			$updateCandidate = "UPDATE `candidate_registration` SET `EmpID`='$emp_id',`EmpName`='$emp_name',`Gender`='$gender',`Skills`='$skills',`AdditionalSkills`='$addSkill',`EmailId`='$email_id',`StartDate`='$start_date',`ContactNo`='$contact_no',`Expereince`='$expereince',`RelevantExperience`='$relevant_experience',`DNAPassScore`='$dna_passscore',`isExternal`='$isExternal',`UpdatedDate`='$updated_date',`UpdatedBy`='$updated_by',`candidate_image`='$candidateImage' WHERE `ID`=$candidateId";
			$resultUpdateCandiadate = mysqli_query($conn,$updateCandidate);
			
				$queryCandidate = "INSERT INTO `candidate_event` (`CanidateID`, `EventID`, `CreatedDate`, `CreatedBy`) VALUES ($candidateId, $event_id, '$created_date', '$created_by')";
				
				$resultCandiadate = mysqli_query($conn,$queryCandidate);
				
				$errcode = 200;
				$status = "Success";
			
		} else if(mysqli_num_rows($select_result1) == 0 && mysqli_num_rows($select_result) == 0){
	
			$query = "INSERT INTO candidate_registration (EmpID, EmpName, Gender, Skills, AdditionalSkills , EmailId , StartDate, ContactNo, Expereince, RelevantExperience, DNAPassScore, isExternal , CreatedDate, CreatedBy,candidate_image ) VALUES ($emp_id, '$emp_name', '$gender', '$skills','$addSkill' ,'$email_id' , '$start_date', '$contact_no', '$expereince', '$relevant_experience', '$dna_passscore', '$isExternal', '$created_date', '$created_by','$candidateImage')";
			
			$result = mysqli_query($conn,$query);
			$candidateId = mysqli_insert_id($conn);
		
			if($result == 1){
				$queryCandidate = "INSERT INTO `candidate_event` (`CanidateID`, `EventID`, `CreatedDate`, `CreatedBy`) VALUES ($candidateId, $event_id, '$created_date', '$created_by')";
				$resultCandiadate = mysqli_query($conn,$queryCandidate);
				
			}else{
				$errcode = 404;
				$status = "Failure";
			}
			
			$errcode = 200;
			$status = "Success";
			
		} else{
			
			$skillrow = mysqli_fetch_assoc($select_result1);
			$candidateId = $skillrow['CanidateID'];

			$updateCandidate = "UPDATE `candidate_registration` SET `EmpID`='$emp_id',`EmpName`='$emp_name',`Gender`='$gender',`Skills`='$skills',`AdditionalSkills`='$addSkill',`EmailId`='$email_id',`StartDate`='$start_date',`ContactNo`='$contact_no',`Expereince`='$expereince',`RelevantExperience`='$relevant_experience',`DNAPassScore`='$dna_passscore',`isExternal`='$isExternal',`UpdatedDate`='$updated_date',`UpdatedBy`='$updated_by',`candidate_image`='$candidateImage' WHERE `ID`=$candidateId";
			$resultUpdateCandiadate = mysqli_query($conn,$updateCandidate);
			
			$errcode = 404;
			$status = "Candidate already allocated to this event";
		}

	} else if($data['EmpID'] == '0' && $isExternal == 1){
		
		$select_query = "SELECT e.`ID`  FROM `candidate_event` e INNER JOIN `candidate_registration` c ON e.`CanidateID`= c.`ID` WHERE c.ContactNo ='$contact_no' and e.EventID = '$event_id' and e.`isActive`=1";
		
		$select_result = mysqli_query($conn,$select_query);
		
		
		$select_query1 = "SELECT c.`ID` as CanidateID  FROM  `candidate_registration` c WHERE c.ContactNo ='$contact_no' and c.`isActive`=1";
		
		$select_result1 = mysqli_query($conn,$select_query1);
		
		
		if(mysqli_num_rows($select_result1) != 0 && mysqli_num_rows($select_result) == 0) {
			$skillrow = mysqli_fetch_assoc($select_result1);
			
			$candidateId = $skillrow['CanidateID'];
			
			$updateCandidate = "UPDATE `candidate_registration` SET `EmpName`='$emp_name',`Gender`='$gender',`Skills`='$skills',`AdditionalSkills`='$addSkill',`EmailId`='$email_id',`StartDate`='$start_date',`ContactNo`='$contact_no',`Expereince`='$expereince',`RelevantExperience`='$relevant_experience',`DNAPassScore`='$dna_passscore',`isExternal`='$isExternal',`UpdatedDate`='$updated_date',`UpdatedBy`='$updated_by',`candidate_image`='$candidateImage' WHERE `ID`=$candidateId";
			$resultUpdateCandiadate = mysqli_query($conn,$updateCandidate);
			
					$queryCandidate = "INSERT INTO `candidate_event` (`CanidateID`, `EventID`, `CreatedDate`, `CreatedBy`) VALUES ($candidateId, $event_id, '$created_date', '$created_by')";
					
					$resultCandiadate = mysqli_query($conn,$queryCandidate);
					
				$errcode = 200;
				$status = "Success";
				
		} else if(mysqli_num_rows($select_result1) == 0 && mysqli_num_rows($select_result) == 0){
		
			$query = "INSERT INTO candidate_registration (EmpName, Gender, Skills, AdditionalSkills , EmailId , StartDate, ContactNo, Expereince, RelevantExperience, DNAPassScore, isExternal , CreatedDate, CreatedBy,candidate_image ) VALUES ('$emp_name', '$gender', '$skills','$addSkill' ,'$email_id' , '$start_date', '$contact_no', '$expereince', '$relevant_experience', '$dna_passscore','$isExternal', '$created_date', '$created_by','$candidateImage')";
				
				$result = mysqli_query($conn,$query);
				$candidateId = mysqli_insert_id($conn);
			
				if($result == 1){
					$queryCandidate = "INSERT INTO `candidate_event` (`CanidateID`, `EventID`, `CreatedDate`, `CreatedBy`) VALUES ($candidateId, $event_id, '$created_date', '$created_by')";
					$resultCandiadate = mysqli_query($conn,$queryCandidate);
					
				}else{
					$errcode = 404;
					$status = "Failure";
				}

				$errcode = 200;
				$status = "Success";
		} else{
			$skillrow = mysqli_fetch_assoc($select_result1);
			$candidateId = $skillrow['CanidateID'];

			$updateCandidate = "UPDATE `candidate_registration` SET `EmpName`='$emp_name',`Gender`='$gender',`Skills`='$skills',`AdditionalSkills`='$addSkill',`EmailId`='$email_id',`StartDate`='$start_date',`ContactNo`='$contact_no',`Expereince`='$expereince',`RelevantExperience`='$relevant_experience',`DNAPassScore`='$dna_passscore',`isExternal`='$isExternal',`UpdatedDate`='$updated_date',`UpdatedBy`='$updated_by',`candidate_image`='$candidateImage' WHERE `ID`=$candidateId";
			$resultUpdateCandiadate = mysqli_query($conn,$updateCandidate);
			
			$errcode = 404;
			$status = "Candidate already allocated to this event";
		}
	}


	echo $result = json_encode(array("errCode"=>$errcode,"status"=>$status));

mysqli_close($conn);
?>