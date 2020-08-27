<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: GET, POST, PATCH, PUT, DELETE, OPTIONS");
header("Access-Control-Allow-Headers: Origin, Content-Type, X-Auth-Token");

$json = file_get_contents('php://input');
$data = json_decode($json,true);


// $data['SquadID'] = 3 ;
// $data['CandidateID'] =array(4,3);
// $data['EventID'] = 1 ;
// $data['isActive'] = 0 ;
// $data['CreatedBy'] = 0 ;
// $data['UpdatedBy'] = 0 ;


		require_once 'include/dbconnect.php';
		
		$Candidate_ID_val = $data['CandidateID'];

	  if($Candidate_ID_val &&  (count($Candidate_ID_val) > 0)){
			
			$cnt = 0;
		  foreach($Candidate_ID_val as $candidate_id){
			
			$Squad_ID			 		= $data['SquadID'];
			$Event_ID			 		= $data['EventID'];
			$Candidate_ID			 	= $candidate_id;
			//$is_Active 			 		= $data['isActive'];
			$is_Active 			 		= ($data['isActive']== true ? 1 : 0);
			$Created_Date				= date('Y-m-d h:i:s');
			$Created_By	 			    = $data['CreatedBy'];
			$UpdatedDate 			    = date('Y-m-d h:i:s');
			$Updated_By				    = $data['UpdatedBy'];

			if($cnt == 0){

			   $del_query = "delete FROM squad_candidates where SquadID = '$Squad_ID'  and EventID = '$Event_ID'";
			   $del_result = mysqli_query($conn,$del_query);
			}

				$query = "INSERT INTO squad_candidates (SquadID, CandidateID, EventID, isActive, CreatedDate,
				CreatedBy, UpdatedDate, UpdatedBy) VALUES ('$Squad_ID', '$Candidate_ID', '$Event_ID', 
				'$is_Active', '$Created_Date', '$Created_By', '$UpdatedDate', '$Updated_By'); ";

				 $result = mysqli_query($conn,$query);

				$cnt++;

			}

			if($result == 1){
				$errcode = 200;
				$status = "Success";
			}else{
				$errcode = 404;
				$status = "Failure";
			}	
			
			
		echo $result = json_encode(array("errCode"=>$errcode,"status"=>$status));
	  
	  }



mysqli_close($conn);
?>