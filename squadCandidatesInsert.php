<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");

$json = file_get_contents('php://input');
$data = json_decode($json,true);

require_once 'include/dbconnect.php';


	  $Candidate_ID_val = $data['CandidateID'];

	  if($Candidate_ID_val &&  (count($Candidate_ID_val) > 0)){
		  foreach($Candidate_ID_val as $candidate_id){
			
			$Squad_ID			 		= $data['SquadID'];
			$Candidate_ID			 	= $candidate_id;
			$is_Active 			 		= $data['isActive'];
			$Created_Date				= date('Y-m-d h:i:s');
			$Created_By	 			    = $data['CreatedBy'];
			$UpdatedDate 			    = date('Y-m-d h:i:s');
			$Updated_By				    = $data['UpdatedBy'];


		$query = "INSERT INTO squad_candidates (SquadID, CandidateID, isActive, CreatedDate, CreatedBy, UpdatedDate, UpdatedBy) VALUES ('$Squad_ID', '$Candidate_ID', '$is_Active', '$Created_Date', '$Created_By', '$UpdatedDate', '$Updated_By'); ";

		$result = mysqli_query($conn,$query);

			}
	  
	  }

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