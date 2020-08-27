<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: GET, POST, PATCH, PUT, DELETE, OPTIONS");
header("Access-Control-Allow-Headers: Origin, Content-Type, X-Auth-Token");

$json = file_get_contents('php://input');
$data = json_decode($json,true);

require_once 'include/dbconnect.php';

	$squad_name	= $data['SquadName'];
	$event_id	= $data['EventID'];
	$created_date = $data['CreatedDate'];
	$created_by	= $data['CreatedBy'];
	$updated_by	= $data['UpdatedBy'];
	$updated_date = $data['UpdatedDate'];
	$teamImg = isset($data['teamImg']) ? $data['teamImg'] : '';

	$selQry = "SELECT * FROM squad WHERE EventID ='$event_id' and SquadName ='$squad_name' ";
	$result = mysqli_query($conn,$selQry);
	if(mysqli_num_rows($result)==0){

		$query = "INSERT INTO squad (SquadName, EventID, CreatedDate, CreatedBy, UpdatedDate, UpdatedBy, squad_team_img ) VALUES ('$squad_name', $event_id, '$created_date', $created_by, '$updated_date', $updated_by,'$teamImg')";
		$result = mysqli_query($conn,$query);
		$squadId = mysqli_insert_id($conn);
		if($squadId > 0){
			$errcode = 200;
			$status = "Success";
			$squadId = mysqli_insert_id($conn);
		}else{
			$errcode = 404;
			$status = "Failure";
			$squadId = "";
		}
	}else{
		$errcode = 404;
		$status = "Squad name already exist";
		$squadId = "";
	}	
	
	echo $result = json_encode(array("errCode"=>$errcode,"status"=>$status,"squadId"=>$squadId));

mysqli_close($conn);
?>