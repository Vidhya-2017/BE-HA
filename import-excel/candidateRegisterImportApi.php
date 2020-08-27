<?php
error_reporting(0);
ini_set('display_errors', FALSE);
ini_set('display_startup_errors', FALSE);

header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json;");
header("Access-Control-Allow-Methods: GET, POST, PATCH, PUT, DELETE, OPTIONS");
header("Access-Control-Allow-Headers: Origin, Content-Type, X-Auth-Token");

$json = file_get_contents('php://input');
$data_res = json_decode($json,true);
$connect = mysqli_connect("localhost", "root", "", "hacker_anchor");
$output = '';
$data = $data_res['data'];
  include("PHPExcel/IOFactory.php"); // Add PHPExcel Library in this code
  include_once('PHPExcel.php');
  
  $file_name = "import".date('YmdHis');
  $target_file = "import_excel/".$file_name.".xlsx";
  $content = base64_decode($data);
  $file_data = fopen($target_file, 'w');
  fwrite($file_data, $content);
  fclose($file_data);
  $file = $target_file;
  $objPHPExcel = PHPExcel_IOFactory::load($file); // create object of PHPExcel library by using load() method and in load method define path of selected file

  $output .= "<label class='text-success'>Data Inserted</label><br /><table class='table table-bordered'>";
  
  foreach ($objPHPExcel->getWorksheetIterator() as $worksheet)
  {
   $highestRow = $worksheet->getHighestRow();
   for($row=2; $row<=$highestRow; $row++)
   {
    $output .= "<tr>";
     
     $skillsname            =   mysqli_real_escape_string($connect, trim($objPHPExcel->getActiveSheet()->getCell('A' . $row)->getValue()));
     $emp_name              =   mysqli_real_escape_string($connect, trim($objPHPExcel->getActiveSheet()->getCell('B' . $row)->getValue()));
     $contact_no            =   mysqli_real_escape_string($connect, trim($objPHPExcel->getActiveSheet()->getCell('C' . $row)->getValue()));
     $gender                =   mysqli_real_escape_string($connect, trim($objPHPExcel->getActiveSheet()->getCell('D' . $row)->getValue()));
     $email_id              =   mysqli_real_escape_string($connect, trim($objPHPExcel->getActiveSheet()->getCell('E' . $row)->getValue()));
     $expereince            =   mysqli_real_escape_string($connect, trim($objPHPExcel->getActiveSheet()->getCell('F' . $row)->getValue()));
     $relevant_experience   =   mysqli_real_escape_string($connect, trim($objPHPExcel->getActiveSheet()->getCell('G' . $row)->getValue()));
     $addSkill              =   mysqli_real_escape_string($connect, trim($objPHPExcel->getActiveSheet()->getCell('H' . $row)->getValue()));    

     $emp_id	            =   '0';
     //$EventDate	            =   $data['EventDate'];
     $event_id	            =   $data_res['EventId'];
     $isExternal            =  ($data_res['isExternal'] == true ? 1 : 0);
     $candidateImage        =  '';
     $start_date	        =  '';
     $created_date          =  '';
	 $created_by	        =  ''; 
	 $updated_date          =  '';
	 $updated_by	        =  '';    
     
     $skills_query          =  "SELECT SkillId FROM  `skills` WHERE Skills ='$skillsname' and `isActive`=1";		
	 $skills_result         =  mysqli_query($connect,$skills_query);
     if(mysqli_num_rows($skills_result) > 0 ) {
     $skillrow = mysqli_fetch_assoc($skills_result);
	    $skills = $skillrow['SkillId'];
     }   
     
// print $isExternal;
// exit;
    // $query = "INSERT INTO candidate_registration (Skills, EmpName , ContactNo , Gender, EmailId, Expereince, RelevantExperience, AdditionalSkill )             VALUES ('".$SkillId."', '".$emp_name."', '".$contact_no."','".$gender."' ,'".$email_id."' , '".$start_date."', '".$expereince."', '".$relevant_experience."', '".$relevant_experience."', '".$addSkill."')";

    // $result = mysqli_query($connect,$query);
    // $candidateId = mysqli_insert_id($connect);

    // if($result == 1){
    //     $queryCandidate = "INSERT INTO `candidate_event` (`CanidateID`, `EventID`, `CreatedDate`, `CreatedBy`) VALUES ($candidateId, $event_id, '$created_date', '$created_by')";
    //     $resultCandiadate = mysqli_query($connect,$queryCandidate);
            
    // }else{
    //     $errcode = 404;
    //     $status = "Failure";
    // }

    // $errcode = 200;
    // $status = "Success";

    if($isExternal == 0)
	{
    	$select_query = "SELECT e.`ID`  FROM `candidate_event` e INNER JOIN `candidate_registration` c ON e.`CanidateID`= c.`ID` WHERE c.ContactNo ='$contact_no' and e.EventID = '$event_id' and e.`isActive`=1";		
		$select_result = mysqli_query($connect,$select_query);	
		
		$select_query1 = "SELECT c.`ID` as CanidateID  FROM  `candidate_registration` c WHERE c.ContactNo ='$contact_no' and c.`isActive`=1";		
		$select_result1 = mysqli_query($connect,$select_query1);

		if(mysqli_num_rows($select_result1) != 0 && mysqli_num_rows($select_result) == 0) {            
			$skillrow = mysqli_fetch_assoc($select_result1);
			$candidateId = $skillrow['CanidateID'];
			
			$updateCandidate = "UPDATE `candidate_registration` SET `EmpID`='$emp_id',`EmpName`='$emp_name',`Gender`='$gender',`Skills`='$skills',`AdditionalSkills`='$addSkill',`EmailId`='$email_id',`StartDate`='$start_date',`ContactNo`='$contact_no',`Expereince`='$expereince',`RelevantExperience`='$relevant_experience',`isExternal`='$isExternal',`UpdatedDate`='$updated_date',`UpdatedBy`='$updated_by',`candidate_image`='$candidateImage' WHERE `ID`=$candidateId";
			$resultUpdateCandiadate = mysqli_query($connect,$updateCandidate);
			
			$queryCandidate = "INSERT INTO `candidate_event` (`CanidateID`, `EventID`, `CreatedDate`, `CreatedBy`) VALUES ($candidateId, $event_id, '$created_date', '$created_by')";				
			$resultCandiadate = mysqli_query($connect,$queryCandidate);
				
			$errcode = 200;
			$status = "Success";
			
		} else if(mysqli_num_rows($select_result1) == 0 && mysqli_num_rows($select_result) == 0){
            
			$query = "INSERT INTO candidate_registration (EmpID, EmpName, Gender, Skills, AdditionalSkills , EmailId , StartDate, ContactNo, Expereince, RelevantExperience, isExternal , CreatedDate, CreatedBy,candidate_image ) VALUES ('$emp_id', '$emp_name', '$gender', '$skills','$addSkill' ,'$email_id' , '$start_date', '$contact_no', '$expereince', '$relevant_experience', '$isExternal', '$created_date', '$created_by','$candidateImage')";					
            $result = mysqli_query($connect,$query);
			$candidateId = mysqli_insert_id($connect);
		
			if($result == 1){
				$queryCandidate = "INSERT INTO `candidate_event` (`CanidateID`, `EventID`, `CreatedDate`, `CreatedBy`) VALUES ($candidateId, $event_id, '$created_date', '$created_by')";
				$resultCandiadate = mysqli_query($connect,$queryCandidate);
				
			}else{
				$errcode = 404;
				$status = "Failure";
			}
			
			$errcode = 200;
			$status = "Success";
			
		} else{
			
			$skillrow = mysqli_fetch_assoc($select_result1);
			$candidateId = $skillrow['CanidateID'];

			$updateCandidate = "UPDATE `candidate_registration` SET `EmpID`='$emp_id',`EmpName`='$emp_name',`Gender`='$gender',`Skills`='$skills',`AdditionalSkills`='$addSkill',`EmailId`='$email_id',`StartDate`='$start_date',`ContactNo`='$contact_no',`Expereince`='$expereince',`RelevantExperience`='$relevant_experience',`isExternal`='$isExternal',`UpdatedDate`='$updated_date',`UpdatedBy`='$updated_by',`candidate_image`='$candidateImage' WHERE `ID`=$candidateId";
			$resultUpdateCandiadate = mysqli_query($connect,$updateCandidate);
			
			$errcode = 404;
			$status = "Candidate already allocated to this event";
		}

	} else if($isExternal == 1){
		
		$select_query = "SELECT e.`ID`  FROM `candidate_event` e INNER JOIN `candidate_registration` c ON e.`CanidateID`= c.`ID` WHERE c.ContactNo ='$contact_no' and e.EventID = '$event_id' and e.`isActive`=1";		
		$select_result = mysqli_query($connect,$select_query);	
		
		$select_query1 = "SELECT c.`ID` as CanidateID  FROM  `candidate_registration` c WHERE c.ContactNo ='$contact_no' and c.`isActive`=1";		
		$select_result1 = mysqli_query($connect,$select_query1);		
		
		if(mysqli_num_rows($select_result1) != 0 && mysqli_num_rows($select_result) == 0) {
            
			$skillrow = mysqli_fetch_assoc($select_result1);			
			$candidateId = $skillrow['CanidateID'];
			
			$updateCandidate = "UPDATE `candidate_registration` SET `EmpName`='$emp_name',`Gender`='$gender',`Skills`='$skills',`AdditionalSkills`='$addSkill',`EmailId`='$email_id',`StartDate`='$start_date',`ContactNo`='$contact_no',`Expereince`='$expereince',`RelevantExperience`='$relevant_experience',`isExternal`='$isExternal',`UpdatedDate`='$updated_date',`UpdatedBy`='$updated_by',`candidate_image`='$candidateImage' WHERE `ID`=$candidateId";
			$resultUpdateCandiadate = mysqli_query($connect,$updateCandidate);
			
			$queryCandidate = "INSERT INTO `candidate_event` (`CanidateID`, `EventID`, `CreatedDate`, `CreatedBy`) VALUES ($candidateId, $event_id, '$created_date', '$created_by')";					
			$resultCandiadate = mysqli_query($connect,$queryCandidate);
					
			$errcode = 200;
			$status = "Success";
				
		} else if(mysqli_num_rows($select_result1) == 0 && mysqli_num_rows($select_result) == 0){
            
			$query = "INSERT INTO candidate_registration (EmpName, Gender, Skills, AdditionalSkills , EmailId , StartDate, ContactNo, Expereince, RelevantExperience, isExternal , CreatedDate, CreatedBy,candidate_image )             
            VALUES ('".$emp_name."', '".$gender."', '".$skills."','".$addSkill."' ,'".$email_id."' , '".$start_date."', '".$contact_no."', '".$expereince."', '".$relevant_experience."', '".$isExternal."', '".$created_date."', '".$created_by."','".$candidateImage."')";
				
			$result = mysqli_query($connect,$query);
			$candidateId = mysqli_insert_id($connect);
			//print $result;
            //exit;
			if($result == 1){
				$queryCandidate = "INSERT INTO `candidate_event` (`CanidateID`, `EventID`, `CreatedDate`, `CreatedBy`) VALUES ($candidateId, $event_id, '$created_date', '$created_by')";
				$resultCandiadate = mysqli_query($connect,$queryCandidate);
					
			}else{
				$errcode = 404;
				$status = "Failure";
			}

			$errcode = 200;
			$status = "Success";
		} else{
			$skillrow = mysqli_fetch_assoc($select_result1);
			$candidateId = $skillrow['CanidateID'];

			$updateCandidate = "UPDATE `candidate_registration` SET `EmpName`='$emp_name',`Gender`='$gender',`Skills`='$skills',`AdditionalSkills`='$addSkill',`EmailId`='$email_id',`StartDate`='$start_date',`ContactNo`='$contact_no',`Expereince`='$expereince',`RelevantExperience`='$relevant_experience',`isExternal`='$isExternal',`UpdatedDate`='$updated_date',`UpdatedBy`='$updated_by',`candidate_image`='$candidateImage' WHERE `ID`=$candidateId";
			$resultUpdateCandiadate = mysqli_query($connect,$updateCandidate);
			
			$errcode = 404;
			$status = "Candidate already allocated to this event";
		}
	}

   }
  } 
?>

   <?php
   echo $result = json_encode(array("errCode"=>$errcode,"status"=>$status));
   ?>
  
