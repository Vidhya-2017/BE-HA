<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");

require_once 'include/dbconnect.php';
$type = $_POST['type'];



  if(isset($type)){
	
	    if($type == 'eventDetails'){
			
			$event_ID = $_POST['event_id'];
			
			$query = "select cs.EmpID,cs.EmpName,ce.EventID,re.EventName, (case when ce.isSelected = 1 THEN 'True' else 'False'  END) as isSelected, (case when ce.isActive = 1 THEN 'Active' else 'InActive'  END) as isActive from candidate_registration as cs left outer join candidate_event ce on ce.CanidateID = cs.ID left outer join register_event re on ce.EventID = re.EventID where re.EventID =  '$event_ID'";
				
		}else if($type == 'candidateDetails'){
			
			$candidate_ID = $_POST['candidate_id'];
			
			$query = "select cs.EmpID,cs.EmpName,ce.EventID,re.EventName, (case when ce.isSelected = 1 THEN 'True' else 'False'  END) as isSelected, (case when ce.isActive = 1 THEN 'Active' else 'InActive'  END) as isActive from candidate_registration as cs left outer join candidate_event ce on ce.CanidateID = cs.ID left outer join register_event re on ce.EventID = re.EventID where cs.EmpID =  '$candidate_ID'";
		}
		
		
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
			
   }
   
mysqli_close($conn);
?>