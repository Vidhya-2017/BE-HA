<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: GET, POST, PATCH, PUT, DELETE, OPTIONS");
header("Access-Control-Allow-Headers: Origin, Content-Type, X-Auth-Token");

$json = file_get_contents('php://input');
$data = json_decode($json,true);

require_once 'include/dbconnect.php';

	$squad_id	= $data['SquadID'];
	$event_id	= $data['EventID'];
	$updated_by	= $data['UpdatedBy'];
	$updated_date = $data['UpdatedDate'];
	$teamImg = isset($data['teamImg']) ? $data['teamImg'] : '';

	$selQry = "SELECT * FROM squad WHERE EventID ='$event_id' and ID ='$squad_id'";
	$result = mysqli_query($conn,$selQry);
	if(mysqli_num_rows($result)==1){
        
        $query = "UPDATE squad SET  squad_team_img ='$teamImg',UpdatedDate ='$updated_date',UpdatedBy ='$updated_by' WHERE EventID ='$event_id' and ID ='$squad_id' ";
		$result = mysqli_query($conn,$query);
		$isUpdate = mysqli_affected_rows($conn);
		if($isUpdate > 0){
			$errcode = 200;
			$status = "Success";
			
		}else{
			$errcode = 404;
			$status = "Failure";
			
		}
	}else{
		$errcode = 404;
		$status = "No Result";
		
	}	
	
	echo $result = json_encode(array("errCode"=>$errcode,"status"=>$status));

mysqli_close($conn);
?>