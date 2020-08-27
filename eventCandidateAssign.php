<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: GET, POST, PATCH, PUT, DELETE, OPTIONS");
header("Access-Control-Allow-Headers: Origin, Content-Type, X-Auth-Token");

$json = file_get_contents('php://input');
$data = json_decode($json,true);

require_once 'include/dbconnect.php';

if(isset($data)){
	$event_id	= $data['eventID'];
	$candidate_ids	= $data['candidateIDs'];
	$updated_by	= $data['UpdatedBy'];
	$updated_date = date('Y-m-d h:i:s');

    $selQry = "SELECT `CanidateID` FROM `candidate_event` WHERE `EventID` ='$event_id'";
    //echo $selQry;die;
	$result = mysqli_query($conn,$selQry);
	
        $delQuery = "DELETE FROM `candidate_event` WHERE `EventID` ='$event_id'";
        $result = mysqli_query($conn,$delQuery);
   
		
    
        $insertedId=[];
        foreach($candidate_ids as $data){
            $query = "INSERT INTO `candidate_event`(`CanidateID`, `EventID`, `UpdatedDate`, `UpdatedBy`) VALUES ('$data','$event_id','$updated_date','$updated_by')";
            $result = mysqli_query($conn,$query);
            $candidateId = mysqli_insert_id($conn);
            $insertedId[]= $candidateId;
        }

		if(count($insertedId) > 0){
			$errcode = 200;
			$status = "Success";
		}else{
			$errcode = 404;
			$status = "Failure";
		}
    }else{
        $errcode = 404;
        $status = "Request cannot be Null";
    }

	echo $result = json_encode(array("errCode"=>$errcode,"status"=>$status));

mysqli_close($conn);
?>